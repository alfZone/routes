# routes
The goal of this package is to implement a routing mechanism to present beautiful URLs. With this mechanism, all requests are made in the public folder and then redirected to a specific file according to the route (URL) written.

#version
Version 1.0
2023/03/06

# install
1. Copy all files to the website root.

2. Update .htaccess for the actual configuration. Change line 3 to set for the url to public folder.

edit the routes file to match a route to a file. Each route or set of routes should correspond to a website service.
In order to edit the routes.php file to make the correspondence you need to open the file and add the desired route and the corresponding service. For example, if you want to match the route "/my-route" to the "my-service" service, you can add the following line to the routes.php file:

Route::get('/my-route', 'MyService@index');
This will make it so that when a request is sent to the "/my-route" URL, the "MyService" service is invoked. You can also use route parameters and wildcards to make more complex matches. For more information on the routing system in PHP, please refer to the official documentation.
