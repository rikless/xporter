## Laravel CSV Xporter

Xporter is a CSV wrapper allowing you to export Eloquent query results from your laravel application, using league/csv package.
With help of Laravels Xporter class creates exportable schema that can be easy modified and reused.

##Documentation

###Installation
Run :
```
composer require rikless/xporter:1.*
```

###Example usage
Create a simple class where you'll have all your export parameter anywhere in your application. App\Exports may be a good place to start.
You'll need to extend the Rikless\Xporter\Exportable class.

Now complete convert(), query() methods and xportable, rootModel properties.

You have to use the convert() methods to transform your data, and add data from relationship.

In your query method, you just need to instantiate your model and add your query. It can be anything from a Request object in your controller.
Be aware to not use the get() or all() method in you query, because the package will chunk results. This mean that it needs an Illuminate\Database\Eloquent\Builder object.

```php
<?php

namespace App\Exports;

use Rikless\Xporter\Exportable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersExporter extends Exportable
{

    protected $xportable = ['email', 'uuid'];

    protected $rootModel = \App\User::class;

    public function convert($item)
    {
        return [
            'email' => $item->email,
            'uuid' => $item->name,
        ];
    }

    public function query(Request $request)
    {
        return (new $this->rootModel)->where('created_at', '<=', Carbon::now());
    }

}
```

And now in your controller :

```php
<?php

namespace App\Http\Controllers;

use App\Exports\UsersExporter;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function store(UsersExporter $usersExporter, Request $request)
    {
        return $usersExporter->build($request);
    }
}
```

### License
Licensed under the [MIT license](http://opensource.org/licenses/MIT).

