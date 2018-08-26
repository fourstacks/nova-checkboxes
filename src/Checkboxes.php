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

    public function displayUncheckedValues()
    {
        return $this->withMeta([
            'display_unchecked' => true
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

    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        if ($this->attributeExists($request, $requestAttribute)) {

            if($this->shouldSaveAsString()){
                $value = $request[$requestAttribute];
            }
            elseif($this->shouldSaveUnchecked()){
                $value = $this->withUnchecked($request[$requestAttribute]);
            }
            else {
                $value = explode(',', $request[$requestAttribute]);
            }
            $model->{$attribute} = $value;
        }
    }


    private function attributeExists(NovaRequest $request, $requestAttribute)
    {
        return (
            $request->exists($requestAttribute)
            && ! is_null($request[$requestAttribute])
        );
    }

    private function shouldSaveAsString()
    {
       return (
           array_key_exists('saved_as_string', $this->meta)
           && $this->meta['saved_as_string']
       );
    }

    private function shouldSaveUnchecked()
    {
        return (
            array_key_exists('saved_unchecked', $this->meta)
            && $this->meta['saved_unchecked']
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
