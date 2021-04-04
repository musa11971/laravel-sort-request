<p align="center"><img src=".github/logo.png" width="400"></p>

<p align="center">
<a href="https://packagist.org/packages/musa11971/laravel-sort-request"><img src="https://img.shields.io/packagist/v/musa11971/laravel-sort-request.svg?style=flat-square" alt="Latest version on packagist"></a>
<a href="https://github.com/musa11971/laravel-sort-request/actions?query=workflow%3Arun-tests+branch%3Amaster"><img src="https://img.shields.io/github/workflow/status/musa11971/laravel-sort-request/run-tests?label=tests" alt="GitHub Tests Action Status"></a>
<a href="https://scrutinizer-ci.com/g/musa11971/laravel-sort-request"><img src="https://img.shields.io/scrutinizer/g/musa11971/laravel-sort-request.svg?style=flat-square" alt="Quality score"></a>
<a href="https://packagist.org/packages/musa11971/laravel-sort-request"><img src="https://img.shields.io/packagist/dt/musa11971/laravel-sort-request.svg?style=flat-square" alt="Total downloads"></a>
</p>

<p align="center">
  <sup><em>because you've got better things to do</em></sup>
</p>

# Sorting logic for your requests, but simplified

This Laravel package makes it easier to implement sorting logic into your app.  
Consider the following examples:
```bash
# Get the cheapest items
https://example.test/items?sort=price(asc)

# Get the items sorted by name and size
https://example.test/items?sort=name(asc),size(desc)

# Get the most popular TV Shows (custom sorting behavior)
https://example.test/tv-shows?sort=popularity(most-popular)
```

## Installation

You can download a release and manually include it in your project:

| PHP Version   | Sort Request Version  |
| ------------- |-----------------------|
| PHP 7.3       | [Sort Request 1.0](../../releases/tag/1.0.1)             |
| PHP 7.4       | [Sort Request 2.0](../../releases/tag/2.0)               |
| PHP 8.0       | [Sort Request 2.0](../../releases/tag/2.0)               |

Alternatively you can install the package via composer:

```bash
composer require musa11971/laravel-sort-request
```

## Usage
### Basic sorting
Add the `SortsViaRequest` trait to your [Laravel form request](https://laravel.com/docs/6.x/validation#form-request-validation).

```php
use musa11971\SortRequest\Tests\Support\Requests\FormRequest;
use musa11971\SortRequest\Traits\SortsViaRequest;

class GetItemsRequest extends FormRequest
{
    use SortsViaRequest;

    /**
     * Get the rules that the request enforces.
     *
     * @return array
     */
    function rules()
    {
        return array_merge([
            // This is where your normal validation rules go
        ], $this->sortingRules());
    }

    /**
     * Returns the columns that can be sorted on.
     *
     * @return array
     */
    function getSortableColumns(): array
    {
        return [
            'id', 'stackSize', 'displayName'
        ];
    }
}
```
As shown above, you will also need to implement the `getSortableColumns` method into your form request. It should return an array of column names that can be sorted on.  
So if you only wanted to allow sorting on the "name" and "price" columns, you would do:  
```php
function getSortableColumns(): array
{
    return ['name', 'price'];
}
```

Next, go to your controller and add the `sortViaRequest` method as follows:

```php
use Illuminate\Routing\Controller;
use musa11971\SortRequest\Tests\Support\Models\Item;
use musa11971\SortRequest\Tests\Support\Requests\GetItemsRequest;

class ItemController extends Controller
{
    /**
     * Returns a list of all items as JSON.
     *
     * @param GetItemsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    function get(GetItemsRequest $request)
    {
        $items = Item::sortViaRequest($request)->get();

        // Do something with your models...
    }
}
```

😎 That's all. You can now sort the models with the "sort" parameter.
```bash
# Sort a single column
https://example.test/items?sort=price(asc)

# Sort multiple columns
https://example.test/items?sort=price(asc),name(desc),experience(asc)
```

### Custom sorting
This package also allows you to have custom sorting behavior, like the following examples:
```bash
# Get the worst ranking users
https://example.test/user?sort=ranking(worst)

# Get the most delicious pastries, and sort them by cheapest
https://example.test/pastries?sort=taste(most-delicious),price(cheapest)
```

Please refer the [custom sorting docs](docs/CUSTOM_SORTING.md) for a guide on how to use this.

### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mussesemou99@gmail.com instead of using the issue tracker.

## Credits

Credits go to [musa11971](https://github.com/musa11971) for creating and maintaining the package.  

Special thanks  
- .. to [Spatie](https://github.com/spatie) for their [template](https://github.com/spatie/skeleton-php).
- .. to [all contributors](../../contributors) for contributing to the project.

## Support me

I am a full-time software engineering student and work on this package in my free time. If you find the package useful, please consider making a [donation](https://www.paypal.me/musa11971)! Every little bit helps. 💜

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
