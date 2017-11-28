# Package Laravel-Multiselect

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package provides a quick interface for adding select boxes from which multiple elements may be selected.
The interface is similar to the Laravel Collective [HTML](https://github.com/LaravelCollective/html/) package,
and aims to be compatible with it. However, you can use this library without Laravel Collective's HTML.

This package adheres to [PSR 1, 2 and 4](http://www.php-fig.org/psr/).

## Requirements

- [PHP > 5.6](http://php.net)
- [Laravel 5.4|5.5](https://github.com/laravel/framework)
- [jQuery > 2.0?](http://jquery.com)

## Install

Via Composer

``` bash
$ composer require andrechalom/laravel-multiselect
```

### Service Provider & Facade

Register provider and facade on your config/app.php file. This is necessary for Laravel 5.4, but optional for >= 5.5.

```php
'providers' => [
    ...,
    AndreChalom\LaravelMultiselect\MultiselectServiceProvider::class,
]

 'aliases' => [ 
    ...,
    'Multiselect' => AndreChalom\LaravelMultiselect\MultiselectFacade::class,
 ]
```

### Javascript Configuration

The jQuery code required by this package is in the file multiselect.js. If you use Laravel Mix, you can include it
in your mix.js. Otherwise, copy it to your public folder and source it directly in the app layout. 
Remember to do it after you include jQuery.

Example:

```js
mix.js([
    'resources/assets/js/app.js',
    'vendor/andrechalom/laravel-multiselect/resources/assets/js/multiselect.js',
    ], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
```

## Basic Usage

(1) The default behavior creates a select element with an area where the selected options are placed. 
Use any key => value array to populate the select.

``` php
$list = [
    'r' => 'red',
    'g' => 'green',
    'b' => 'blue',
];

{!! Multiselect::select('colors', $list) !!}
```

To provide initial selected options, pass a list of keys as the third argument. All keys in this argument are supposed
to be in the options list (but see "Advanced usage" below):

``` php
{!! Multiselect::select('colors', $list, ['r', 'b']) !!}
```

This can be populated from Eloquent models:

``` php
{!! Multiselect::select(
    'colors', 
    Colors::all()->pluck('name', 'id'), 
    isset($person) ? $person->favoriteColors()->pluck('id') : []
) !!}
```

(2) In the Controller, the selected options will be in a array. 
```php
public function update(Request $request, $id) {
    ...
    $this->validate(['colors' => 'array']);
    $person->favoriteColors()->sync($request->colors);
}
```

(3) style: the span elements generated with Multiselect are of the "multiselector" css class. You can style them as you like, for instance

```css
.multiselector { display: inline-block; border: 1px dashed; padding: 2px; margin: 5px; cursor: pointer; }
.multiselector:after { font-family: "Glyphicons Halflings"; content: "\e014"; padding-left: 3px; }
```

A css file is included in this package source code, and includes the above, plus all of the elements that should
be styled when using this package.

If would like to change the class or other HTML attributes of the select, option or span elements generated, use the
following arguments:

``` php
{!! Multiselect::select(
    'colors', 
    $colors, 
    $default_colors,
    ['class' => 'select-class multiselect'],
    [['class' => 'option1-class', 'class' => 'option2-class']],
    ['class' => 'span-class']
) !!}
```

Note that the argument for options excepts an array with the same size of the `$list` parameter. Also note that
if you change the select class, you must append "multiselect" or the Javascript code won't work.

## Advanced usage

(1) A more advanced usage involves generating the `select` and `span` elements separately. To do so, use the `$selectOnly`
argument on the `select` function.

```php
{!! Multiselect::select( 'colors', $colors, $default_colors, [], [], [], true) !!}
# ... somewhere else in the page ...
{!! Multiselect::span( 'colors', $colors, $default_colors, [], false) !!}
```

Notice that you must pass the exact same arguments as `$list` and `$selected` for both functions.

(2) When a value passed as default to the `span` function is not found in the `$list` argument, the function generates
an element with "Undefined" as label. You can change this behavior to throwing and exception using the last argument
of the `span` function (strict mode).

(3) Unlike in LaravelCollective's select, a placeholder option is always generated. You can customize its label using

```php
{!! Multiselect::select( 
    'colors', 
    $colors, 
    $default_colors, 
    ['placeholder' => 'Pick your favorite colors']
) !!}
```

(4) You can also use an autocomplete input instead of a select element! To do so, import 
[devbridge's Autocomplete plugin](https://github.com/devbridge/jQuery-Autocomplete), replace the calls to 
`Multiselect::select()` to `Multiselect::autocomplete()`, and include a `Multiselect::scripts()` call after
jQuery is loaded.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email andrechalom@gmail.com instead of using the issue tracker.

## Credits

The idea behind the Multiselect is based on an article by [Michał Wojciechowski](http://odyniec.net/articles/multiple-select-fields/). This library reuses some code from Laravel Collective [HTML](https://github.com/LaravelCollective/html/).

## License

This work is licensed under the GNU Public License. Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/andrechalom/laravel-multiselect.svg
[ico-license]: https://img.shields.io/badge/license-GPLv3-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/andrechalom/laravel-multiselect/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andrechalom/laravel-multiselect.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andrechalom/laravel-multiselect.svg
[ico-downloads]: https://img.shields.io/packagist/dt/andrechalom/laravel-multiselect.svg

[link-packagist]: https://packagist.org/packages/andrechalom/laravel-multiselect
[link-travis]: https://travis-ci.org/andrechalom/laravel-multiselect
[link-scrutinizer]: https://scrutinizer-ci.com/g/andrechalom/laravel-multiselect/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/andrechalom/laravel-multiselect
[link-downloads]: https://packagist.org/packages/andrechalom/laravel-multiselect
