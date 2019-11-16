*NOTE*

There is now a native field in Nova called Boolean Group that replicates this field when using the `saveUncheckedValues` option.  It is recommended that if you are using this package with that option enabled that you switch to the native Nova field as this package is only sporadically maintained.

---


# A checkbox field for Nova apps

This package contains a Laravel Nova field to add an array of checkboxes to your Nova resource forms and display the input from these fields.


## Installation

You can install this package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require fourstacks/nova-checkboxes
```
## Examples

![Nova checkboxes on Nova index view (with displayUncheckedValuesOnIndex option)](https://raw.githubusercontent.com/fourstacks/nova-checkboxes/master/screenshot-index.png)
Nova checkboxes on Nova index view (with `displayUncheckedValuesOnIndex` option)

![Nova checkboxes on Nova detail view](https://raw.githubusercontent.com/fourstacks/nova-checkboxes/master/screenshot-detail.png)
Nova checkboxes on Nova detail view

![Nova checkboxes on Nova form view](https://raw.githubusercontent.com/fourstacks/nova-checkboxes/master/screenshot-form.png)
Nova checkboxes on Nova form


## Usage

To add a checkbox field, use the `Fourstacks\NovaCheckboxes\Checkboxes` field in your Nova resource:

```php
namespace App\Nova;

use Fourstacks\NovaCheckboxes\Checkboxes;

// ...

class Member extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            Checkboxes::make('Hobbies'),

            // ...
        ];
    }
}
```
If you wish to use this package in it's default configuration then you should also ensure the Eloquent model that your Nova resource represents is casting the attribute you wish to use a checkbox field for, to an array:

```php
namespace App;

// ...

class Member extends Model
{
    protected $casts = [
        'hobbies' => 'array'
    ]
}
```
The exception to this is if you choose to use the `saveAsString` option (see configuration below), in which case you should make sure that you are not casting this attribute to an array.

## Configuration

This package comes with various options that you can use to customise how the values from checkbox fields are saved and displayed:

#### options

Parameters: `array`

Every checkbox field you create should contain `options`.  These control the checkboxes that appear on your form and are comprised of a label and an underlying value:

```php

Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])

```

#### saveAsString

By default, this package will save checked items from this field to an array, in the attributes column in your database:

`[sailing,rock_climbing]`

This requires that you have set the `$casts` property on your eloquent model to an array (see Usage).

However if you wish you can instead save checked items as a simple comma separated string:

`sailing,rock_climbing`

Use the following option to achieve this:

```php

Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])
    ->saveAsString()

```

#### saveUncheckedValues

By default, this field will only save checked items to the database.  However if you wish to save all options that you have set along with whether they are checked or not you can do so by adding this option:

```php

Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])
    ->saveUncheckedValues()

```

This will save results to your database as an object:

`{sailing:true,rock_climbing:false,archery:true}`

Note that in order to use this option you cannot also use the `saveAsString` option - it's one or the other.  Also you must ensure that you have set the `$casts` property on your eloquent model to an array (see Usage).

#### displayUncheckedValuesOnIndex

By default, only the checked options will display on the index.  If you wish to display all the options for this field along with their checked/unchecked status you can add this option:

```php
Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])
    ->displayUncheckedValuesOnIndex()
```
Note that this does NOT require you to save all unchecked values also using `saveUncheckedValues`.  You can use any of the approaches to saving data along with this option.

#### displayUncheckedValuesOnDetail

By default, only the checked options will display on detail screens.  If you wish to display all the options for this field along with their checked/unchecked status you can add this option:

```php
Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])
    ->displayUncheckedValuesOnDetail()
```
Note that this does NOT require you to save all unchecked values also using `saveUncheckedValues`.  You can use any of the approaches to saving data along with this option.

#### Display checkbox in columns

By default, only checked values are displayed in one column, but if you have enough space you can split them in many using the `columns` method on the field.

```php
Checkboxes::make('Hobbies')
    ->options([
        'sailing' => 'Sailing',
        'rock_climbing' => 'Rock Climbing',
        'archery' => 'Archery'
    ])
    ->columns(3)
```
This will render at most 3 columns with values. So if you have 8 values selected you will see 3 columns with 3, 3 and 2 values.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Credits

- [John Wyles](https://github.com/fourstacks)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
