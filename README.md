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

Via Composer (when on Packagist)

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
Remember to do it after you include jquery.

Example:

```js
mix.js([
    'resources/assets/js/app.js',
    'vendor/andrechalom/laravel-multiselect/resources/assets/js/multiselect.js',
    ], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
```

## Usage

- (1) The default behavior creates a select element with an area where the selected options are placed. 
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
to be in the options list:

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

TODO: explain how to generate the span separately; setting attributes for select, options, span.
TODO: document placeholder
TODO: maybe some helper to "auto" pluck?
TODO: maybe some helper to set a single default? 
TODO: include something to catch "undefined offsets"?

If you decide to change the select element class, append "multiselect" to the class definition, or the Javascript code
won't work. Example: `['class' => 'form-control multiselect'].

- (2) In the Controller, the selected options will be in a array. 
```php
public function update(Request $request, $id) {
    ...
    $person->favoriteColors()->sync($request->colors);
}
```

- (3) style: the span elements generated with Multiselect are of the "multiselector" css class. You can style them as you like, for instance

```css
.multiselector { display: inline-block; border: 1px dashed; padding: 2px; margin: 5px; cursor: pointer; }
.multiselector:after { font-family: "Glyphicons Halflings"; content: "\e014"; padding-left: 3px; }
```

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
