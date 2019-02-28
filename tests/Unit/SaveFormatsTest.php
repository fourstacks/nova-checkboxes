<?php

namespace Fourstacks\NovaCheckboxes\Tests\Unit;

use stdClass;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Laravel\Nova\Http\Requests\NovaRequest;
use Fourstacks\NovaCheckboxes\Tests\TestCase;

class SaveFormatsTest extends TestCase
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

    function test_default_to_save_only_unchecked()
    {
        $this->zeroIndexedField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"0":true,"1":false,"2":false,"3":false}'
        ]), $zero = new stdClass);

        $this->assocField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"archery":true,"painting":false,"running":true}'
        ]), $assoc = new stdClass);

        $this->numericKeysField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"1":true,"2":true,"3":false}'
        ]), $numeric = new stdClass);

        $this->assertSame(['0'], $zero->hobbies);
        $this->assertSame(['archery', 'running'], $assoc->hobbies);
        $this->assertSame(['1','2'], $numeric->hobbies);
    }

    function test_default_save_when_nothing_is_checked()
    {
        $this->zeroIndexedField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"0":false,"1":false,"2":false,"3":false}'
        ]), $zero = new stdClass);

        $this->assocField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"archery":false,"painting":false,"running":false}'
        ]), $assoc = new stdClass);

        $this->numericKeysField()->fill((new NovaRequest)->merge([
            'hobbies' => '{"1":false,"2":false,"3":false}'
        ]), $numeric = new stdClass);

        $this->assertSame([], $zero->hobbies);
        $this->assertSame([], $assoc->hobbies);
        $this->assertSame([], $numeric->hobbies);
   }

    function test_save_as_string()
    {
        $this->zeroIndexedField()->saveAsString()->fill((new NovaRequest)->merge([
            'hobbies' => '{"0":true,"1":false,"2":false,"3":false}'
        ]), $zero = new stdClass);

        $this->assocField()->saveAsString()->fill((new NovaRequest)->merge([
            'hobbies' => '{"archery":false,"painting":true,"running":true}'
        ]), $assoc = new stdClass);

        $this->numericKeysField()->saveAsString()->fill((new NovaRequest)->merge([
            'hobbies' => '{"1":true,"2":true,"3":false}'
        ]), $numeric = new stdClass);

        $this->assertSame('0', $zero->hobbies);
        $this->assertSame('painting,running', $assoc->hobbies);
        $this->assertSame('1,2', $numeric->hobbies);
   }

    function test_save_as_string_when_nothing_is_checked_even_if_save_unchecked_values_is_on()
    {
        $this->zeroIndexedField()->saveAsString()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"0":false,"1":false,"2":false,"3":false}'
        ]), $zero = new stdClass);

        $this->assocField()->saveAsString()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"archery":false,"painting":false,"running":false}'
        ]), $assoc = new stdClass);

        $this->numericKeysField()->saveAsString()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"1":false,"2":false,"3":false}'
        ]), $numeric = new stdClass);

        $this->assertSame('', $zero->hobbies);
        $this->assertSame('', $assoc->hobbies);
        $this->assertSame('', $numeric->hobbies);
   }
   
   function test_save_unchecked_values()
    {
        $this->zeroIndexedField()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"0":true,"1":false,"2":false,"3":false}'
        ]), $zero = new stdClass);

        $this->assocField()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"archery":true,"painting":false,"running":true}'
        ]), $assoc = new stdClass);

        $this->numericKeysField()->saveUncheckedValues()->fill((new NovaRequest)->merge([
            'hobbies' => '{"1":false,"2":false,"3":false}'
        ]), $numeric = new stdClass);


        $this->assertEquals((object)[true, false, false, false], $zero->hobbies);
        $this->assertEquals((object)['archery' => true, 'painting' => false, 'running' => true], $assoc->hobbies);
        $this->assertEquals((object)['1' => false, '2' => false, '3' => false], $numeric->hobbies);
    }

}
