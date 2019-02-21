<?php

namespace Fourstacks\NovaCheckboxes\Tests\Unit;

use Fourstacks\NovaCheckboxes\Checkboxes;
use Fourstacks\NovaCheckboxes\Tests\TestCase;

class CheckedOptionsForVueFormFieldTest extends TestCase
{
    protected function assocField()
    {
        return Checkboxes::make('Hobbies')->options([
            'archery' => 'Archery',
            'painting' => 'Painting',
            'running' => 'Running',
        ]);
    }

    protected function numericKeysField()
    {
        return Checkboxes::make('Hobbies')->options([
            1 => 'One', 
            2 => 'Two', 
            3 => 'Three',
        ]);
    }

    protected function zeroIndexedField()
    {
        return Checkboxes::make('Hobbies')->options([
            0 => 'Zero', 
            1 => 'One', 
            2 => 'Two', 
            3 => 'Three',
        ]);
    }

    function test_field_value_when_empty()
    {
        $empty = '{"archery":false,"painting":false,"running":false}';
        $this->assocField()->assertValue($empty, null);
        $this->assocField()->assertValue($empty, '');
        $this->assocField()->assertValue($empty, []);

        $empty = '{"0":false,"1":false,"2":false,"3":false}';
        $this->zeroIndexedField()->assertValue($empty, null);
        $this->zeroIndexedField()->assertValue($empty, '');
        $this->zeroIndexedField()->assertValue($empty, []);

        $empty = '{"1":false,"2":false,"3":false}';
        $this->numericKeysField()->assertValue($empty, null);
        $this->numericKeysField()->assertValue($empty, '');
        $this->numericKeysField()->assertValue($empty, []);
    }

    function test_resolves_field_value_from_array()
    {
        $this->assocField()->assertValue(
            '{"archery":false,"painting":false,"running":true}', ['running' => true, 'painting' => false]
        );

        $this->zeroIndexedField()->assertValue(
            '{"0":false,"1":true,"2":true,"3":false}', [1, 2]
        );

        $this->numericKeysField()->assertValue(
            '{"1":true,"2":true,"3":false}', [1, 2]
        );
    }

    function test_resolves_field_value_from_collection()
    {
        $this->assocField()->assertValue(
            '{"archery":false,"painting":true,"running":false}', collect(['painting' => true])
        );

        $this->zeroIndexedField()->assertValue(
            '{"0":false,"1":true,"2":true,"3":false}', collect([1, 2])
        );

        $this->numericKeysField()->assertValue(
            '{"1":true,"2":true,"3":false}', collect([1, 2])
        );
    }

    function test_resolves_field_value_from_nested_json_attribute()
    {
        $this->assocField()->assertValue(
            '{"archery":true,"painting":false,"running":false}', 
            (object)['selected' => (object)['archery' => true]], 'hobbies->selected'
        );
        
        $this->zeroIndexedField()->assertValue(
            '{"0":false,"1":true,"2":true,"3":false}', 
            (object)['selected' => (object)[1, 2]], 'hobbies->selected'
        );       

        $this->numericKeysField()->assertValue(
            '{"1":true,"2":true,"3":false}', 
            (object)['selected' => (object)[1, 2]], 'hobbies->selected'
        );
    }

    function test_resolves_field_value_from_object()
    {
        $this->assocField()->assertValue(
            '{"archery":false,"painting":false,"running":true}', (object)['running' => true]
        );

        $this->zeroIndexedField()->assertValue(
            '{"0":false,"1":true,"2":true,"3":false}', (object)[1, 2]
        );

        $this->numericKeysField()->assertValue(
            '{"1":true,"2":true,"3":false}', (object)[1, 2]
        );
    }

    function test_resolves_field_value_from_string()
    {
        $this->assocField()->assertValue(
            '{"archery":true,"painting":false,"running":true}', 'running,archery'
        );

        $this->zeroIndexedField()->assertValue('{"0":true,"1":false,"2":true,"3":false}', '0,2');

        $this->numericKeysField()->assertValue('{"1":true,"2":true,"3":false}', '1,2');
    }

    function test_resolves_field_value_from_string_when_option_zero_is_checked()
    {
        // There shouldn't be a case where 0 is passed as an integer, but handle it anyway.
        $this->zeroIndexedField()->assertValue('{"0":true,"1":false,"2":false,"3":false}', 0);
        $this->zeroIndexedField()->assertValue('{"0":true,"1":false,"2":false,"3":false}', '0');
    }

}
