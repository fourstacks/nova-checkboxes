<?php

namespace Fourstacks\NovaCheckboxes;

use Laravel\Nova\Fields\Field;
use Illuminate\Support\Collection;
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

    public function columns($columns = 1)
    {
        return $this->withMeta([
            'columns' => $columns
        ]);
    }

    public function resolveAttribute($resource, $attribute = null)
    {
        $value = data_get($resource, str_replace('->', '.', $attribute));

        if ($value instanceof Collection) {
            $value = $value->toArray();
        } else if (is_object($value)) {
            $value = (array) $value;
        } elseif (is_array($value)) {
            $value = $value;
        } elseif (strlen($value)) {
            $value = explode(',', $value);
        } elseif (! $value) {
            $value = [];
        } 

        if ($this->arrayIsAssoc($value)) {
			$value = $this->onlyChecked($value);
        }

        return json_encode((object)$this->withUnchecked($value));
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->exists($requestAttribute)) {
            $data = json_decode($request[$requestAttribute]);

            if ($this->shouldSaveAsString()) {
                $value = implode(',', $this->onlyChecked($data));
            } elseif ($this->shouldSaveUnchecked()) {
                $value = $data;
            } else {
                $value = $this->onlyChecked($data);
            }

            $model->{$attribute} = $value;
        }
    }

    protected function shouldSaveAsString()
    {
        return (
            array_key_exists('save_as_string', $this->meta)
            && $this->meta['save_as_string']
        );
    }

	protected function shouldSaveUnchecked()
    {
        return (
            array_key_exists('save_unchecked', $this->meta)
            && $this->meta['save_unchecked']
        );
    }

	protected function withUnchecked($data)
    {
        return collect($this->meta['options'])
            ->mapWithKeys(function ($option) use ($data) {
                $isChecked = in_array($option['value'], $data);

                return [ $option['value'] => $isChecked ];
            })
            ->all();
    }

	protected function onlyChecked($data)
    {
        return collect($data)
            ->filter(function ($isChecked) {
                return $isChecked;
            })
            ->map(function ($value, $key) {
                return $key;
            })
            ->values()
            ->all();
    }

	protected function arrayIsAssoc(array $array)
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
