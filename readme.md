## Laravel 5 WithTrait


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

## Requirements

The package has been tested in the following configuration: 

* PHP version &gt;= 7.1.3
* Laravel Framework version &gt;= 5.8

### Install

Require this package with composer using the following command:

```bash
composer require rvkolosov/laravel-withtrait
```


### Configuration

Enable trait in your model:

```php
use RVKolosov\LaravelWithTrait\WithTrait;
use App\Models\Image;

class Post extends Model
{
    use WithTrait;
	
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
```

For index method of controller use ```withRelations()```:

```php
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::withRelations()->get();
    }
}
```

For show method of controller use ```loadRelations()```:

```php
use App\Models\Post;

class PostController extends Controller
{
    public function show(Post $post)
    {
        return $post->loadRelations();
    }
}
```

### Usage

You can dynamically load relations in ```GET``` request.

Load relations for list:

```GET http://example.com/post?with[]=images```

Load relations for one object:

```GET http://example.com/posts/1?with[]=images```

### License

The Laravel WithTrait is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)


[ico-version]: https://img.shields.io/packagist/v/rvkolosov/laravel-withtrait.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rvkolosov/laravel-withtrait/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rvkolosov/laravel-withtrait.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rvkolosov/laravel-withtrait.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rvkolosov/laravel-withtrait.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rvkolosov/laravel-withtrait
[link-travis]: https://travis-ci.org/rvkolosov/laravel-withtrait
[link-scrutinizer]: https://scrutinizer-ci.com/g/rvkolosov/laravel-withtrait/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rvkolosov/laravel-withtrait
[link-downloads]: https://packagist.org/packages/rvkolosov/laravel-withtrait
[link-author]: https://github.com/rvkolosov
[link-contributors]: ../../contributors