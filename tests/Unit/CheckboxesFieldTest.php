<?php

namespace Fourstacks\NovaCheckboxes\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Fourstacks\NovaCheckboxes\Tests\TestCase;

class CheckboxesFieldTest extends TestCase
{
    function test_checkbox_field_can_be_serialized()
    {
        $field = Checkboxes::make('Options');

        $this->assertEquals([
            'component' => 'nova-checkboxes',
            "prefixComponent" => true,
            'name' => 'Options',
            'attribute' => 'options',
            'value' => null,
            'panel' => null,
            'sortable' => false,
            'textAlign' => 'left',
            'indexName' => 'Options'
        ], $field->jsonSerialize());
    }

    function test_checkboxes_field_options_as_an_associative_array()
    {
        $field = Checkboxes::make('Hobbies')->options([
            'sailing' => 'Sailing',
            'rock_climbing' => 'Rock Climbing',
            'archery' => 'Archery'
        ]);

        $this->assertEquals([
            'options' => [
                ['label' => 'Sailing', 'value' => 'sailing'],
                ['label' => 'Rock Climbing', 'value' => 'rock_climbing'],
                ['label' => 'Archery', 'value' => 'archery'],
            ],
        ], $field->meta());
    }

    function test_checkboxes_field_options_as_a_value_only_array()
    {
        $field = Checkboxes::make('Hobbies')->options([
            'Sailing',
            'Rock Climbing',
            'Archery'
        ]);

        $this->assertEquals([
            'options' => [
                ['label' => 'Sailing', 'value' => 0],
                ['label' => 'Rock Climbing', 'value' => 1],
                ['label' => 'Archery', 'value' => 2],
            ],
        ], $field->meta());
    }

    function test_checkboxes_field_can_be_set_to_save_as_string()
    {
        $field = Checkboxes::make('options')->saveAsString();

        $this->assertEquals(['save_as_string' => true], $field->meta());
    }

    function test_checkboxes_field_can_be_set_to_save_unchecked_values()
    {
        $field = Checkboxes::make('options')->saveUncheckedValues();

        $this->assertEquals(['save_unchecked' => true], $field->meta());
    }

    function test_checkboxes_field_can_be_set_to_display_unchecked_values_on_index()
    {
        $field = Checkboxes::make('options')->displayUncheckedValuesOnIndex();

        $this->assertEquals(['display_unchecked_on_index' => true], $field->meta());
    }
    
    function test_checkboxes_field_can_be_set_to_display_unchecked_values_on_detail()
    {
        $field = Checkboxes::make('options')->displayUncheckedValuesOnDetail();

        $this->assertEquals(['display_unchecked_on_detail' => true], $field->meta());
    }
    
    function test_checkboxes_field_can_be_set_to_display_options_in_many_columns()
    {
        $field = Checkboxes::make('options')->columns();

        $this->assertEquals(['columns' => 1], $field->meta());
        $this->assertEquals(['columns' => 4], $field->columns(4)->meta());
    }
}
