<?php

namespace Fourstacks\NovaCheckboxes;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Checkboxes extends Field
{

    public $component = 'nova-checkboxes';


    public function options($options)
    {
        return $this->withMeta([
            'options' => collect($options ?? [])->map(function ($label, $value) {
                return ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }


    public function saveAsString()
    {
        return $this->withMeta([
            'save_as_string' => true
        ]);
    }


    public function saveUncheckedValues()
    {
        return $this->withMeta([
            'save_unchecked' => true
        ]);
    }

    public function displayUncheckedValuesOnIndex()
    {
        return $this->withMeta([
            'display_unchecked_on_index' => true
        ]);
    }

    public function displayUncheckedValuesOnDetail()
    {
        return $this->withMeta([
            'display_unchecked_on_detail' => true
        ]);
    }

    public function resolveAttribute($resource, $attribute = null)
    {
        $value = data_get($resource, $attribute);

        if(! $value) return $value;

        if(is_array($value)){
            if($this->arrayIsAssoc($value)){
                return collect($value)
                    ->filter(function($option){
                        return $option;
                    })
                    ->map(function($option, $key){
                        return $key;
                    })
                    ->values()
                    ->all();
            }
            return $value;
        }

        return explode(',', $value);
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request, $requestAttribute, $model, $attribute
    )
    {
        if ($request->exists($requestAttribute)) {

            if($this->shouldSaveAsString()){
                $value = $request[$requestAttribute];
            }
            elseif($this->shouldSaveUnchecked()){
                $value = $this->withUnchecked($request[$requestAttribute]);
            }
            else {
                $value = ($request[$requestAttribute])
                    ? explode(',', $request[$requestAttribute])
                    : [];
            }
            $model->{$attribute} = $value;
        }
    }

    private function shouldSaveAsString()
    {
        return (
            array_key_exists('save_as_string', $this->meta)
            && $this->meta['save_as_string']
        );
    }

    private function shouldSaveUnchecked()
    {
        return (
            array_key_exists('save_unchecked', $this->meta)
            && $this->meta['save_unchecked']
        );
    }

    private function withUnchecked($checkedOptions)
    {
        $checkedOptionsArray = explode(',', $checkedOptions);

        return collect($this->meta['options'])
            ->mapWithKeys(function($option) use ($checkedOptionsArray){
                $isChecked = in_array($option['value'], $checkedOptionsArray);

                return [ $option['value'] => $isChecked ];
            })
            ->all();
    }

    private function arrayIsAssoc(array $array)
    {
        if ([] === $array) return false;

        return array_keys($array) !== range(0, count($array) - 1);
    }
}