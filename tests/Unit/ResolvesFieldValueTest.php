<?php

namespace Fourstacks\NovaCheckboxes\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Fourstacks\NovaCheckboxes\Tests\TestCase;

class ResolvesFieldValueTest extends TestCase
{
    protected function field()
    {
        return Checkboxes::make('Hobbies')->options([
            'archery' => 'Archery',
            'painting' => 'Painting',
            'running' => 'Running',
        ]);
    }

    function test_resolves_field_value_from_array()
    {
        $this->field()->assertValue(
            '{"archery":false,"painting":false,"running":true}', ['running' => true]
        );
    }

    function test_resolves_field_value_from_collection()
    {
        $this->field()->assertValue(
            '{"archery":false,"painting":false,"running":true}', collect(['running' => true])
        );
    }

    function test_resolves_field_value_from_nested_json_attribute()
    {
        $this->field()->assertValue('{"archery":true,"painting":false,"running":false}', 
            (object)['selected' => (object)['archery' => true]], 'hobbies->selected'
        );
    }

    function test_resolves_field_value_from_object()
    {
        $this->field()->assertValue(
            '{"archery":false,"painting":false,"running":true}', (object)['running' => true]
        );
    }

    function test_resolves_field_value_from_string()
    {
        $this->field()->assertValue('{"archery":true,"painting":false,"running":true}', 
            'running,archery'
        );
    }
}
