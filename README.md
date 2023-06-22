# php_router
Simple php router library for managing your APIs/Apps routes.

This library is only meant for study purposes or small projects.

<br>

<h1>How to install it</h1>

```
composer require richardandrade/php_router
```

<h1>How to use it</h1>
<br>
1. In your Front Controller, you'll need to instanciate the Router Class with your source (or app) namespace as a param:
<br><br>

```
use RichardAndrade\PhpRouter;

$router = new Router('app\example\\');
```
<br>
2. After initializing the class you'll then need to define your application routes, I recommend creating a new file like 'routes.php' and then including it in your Front Controller:
<br><br>

```
include_once('routes.php');
```
```
//in 'routes.php' file

$router->setRoute([
      'path' => '/home', //Route path
      'methods' => ['GET'], //Here you'll need to define the allowed HTTP Methods as an array (e.g ['GET','POST','PUT'])
      'controller' => 'MyController' //The name of the controller responsible for this route
]);
```
<br>
3. Finally, after defining your routes and including the file in the Front Controller, call the following method to start the router:
<br><br>

```
$router->run();
```

The final result (index file) should be something like:

```
use RichardAndrade\PhpRouter;

$router = new Router('app\example\\');

include_once('routes.php');

$router->run();
```
<br>
<h1>Defining dynamic routes</h1>
<br>
With this library you'll also be able to define dynamic routes.
With this type of route your application will receive any params that you defined in the request URI.
<br><br>

```
//routes such as 'myapp.com/article/1', where '1' could be the article ID, can be configured as shown bellow

$router->setRoute([
      'path' => '/article/{id}', //in this case I used 'id', this will be the key name for the 'params' associative array
      'methods' => ['GET'],
      'controller' => 'ArticlesController'
]);

//after that, your controller will be able to receive the defined params

class ArticlesController {
  public funtion __construct(array $params)
  {
  }
}

//the 'params' variable will provide, in this example, ['id' => '1']


```

<br>

<h1>extra: setting headers</h1>
<br>
This library also provides a simple way to define the response headers inside your controllers:
<br><br>

```
use RichardAndrade\PhpRouter;

class ArticlesController {
    public function example()
    {
        Router::setHeaders([
              'Content-type' => 'application/xml',
              'Access-Control-Allow-Origin' => 'https://myapp.com'
        ]);
    }
}
```

