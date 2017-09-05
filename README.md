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

### Service Provider & Facade (Optional on Laravel 5.5)

Register provider and facade on your config/app.php file.

```php
'providers' => [
    ...,
    AndreChalom\LaravelMultiselect\MultiselectServiceProvider::class,
]

// 'aliases' => [ // is this needed???
//    ...,
//    'Multiselect' => AndreChalom\LaravelMultiselect\MultiselectFacade::class,
// ]
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

- (1) Usage in Views, explain list, selected etc with pluck, setting classes WITH WARNING
``` php
$skeleton = new League\Skeleton();
echo $skeleton->echoPhrase('Hello, League!');
```

If you decide to change the select element class, append "multiselect" to the class definition, or the Javascript code
won't work. Example: `['class' => 'form-control multiselect'].

- (2) small example in controller, how to retrieve the data (and sync to model)

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

[ico-version]: https://img.shields.io/packagist/v/:vendor/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/:vendor/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/:vendor/:package_name
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/:vendor/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/:vendor/:package_name
[link-downloads]: https://packagist.org/packages/:vendor/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
