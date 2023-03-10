# microframework
 fast, secure, and efficient PHP framework. Flexible yet pragmatic.
 
 ![Microframework](https://i.imgur.com/pLpdKCS.png)

## Description
PHP micro framework a lightweight and efficient tool for building web applications. It focuses on providing essential features and functionality without the added complexity and overhead of larger frameworks. It follows the Model-View-Controller (MVC) pattern, allowing for a clear separation of concerns and easy maintenance. developers can quickly develop and deploy web applications, without sacrificing performance or scalability.

## File Structure
* index.php: the main entry point of the application
* inc/routes.php: a routing file to creare routes and urls
* inc/functions.php: user defined functions file that are initiated with every route you create
* inc/core.php: the core file of the application
* inc/config.php: a configuration file for the entire application
* inc/views: the templates for the application
* inc/translations: the messages that should be displayed to the client
* inc/init: a folder to store the files for each route
* inc/classes: a folder to store all your classes

## Routing
the routing class allows you to create new pages/urls as you define it;

`
$route->get($uri, $callback)
`
This method registers a route for GET requests. When a GET request is made to the specified URI, the callback function is executed. The $uri parameter should be a string representing the URI of the route. The $callback parameter should be a function that is executed when the route is accessed. The example below demonstrates how to register a route for the home page:
```
$route->get("/", function () {
    $page_title = $language['home_page'];
    require_once ('inc/init/index.php');
});
```
`$route->post($uri, $callback)`
This method registers a route for POST requests. When a POST request is made to the specified URI, the callback function is executed. The $uri parameter should be a string representing the URI of the route. The `$callback` parameter should be a function that is executed when the route is accessed. The example below demonstrates how to register a route for the authentication page:
```
$route->post("/auth", function () {
    require_once('core.php');
    $username = $_POST['username'];
});
```
`$route->any($uri, $callback)`
This method registers a route for any HTTP request method (GET, POST, PUT, DELETE, etc.). When a request is made to the specified URI, the callback function is executed. The $uri parameter should be a string representing the URI of the route. The $callback parameter should be a function that is executed when the route is accessed. The example below demonstrates how to register a route for an API endpoint:
```
$route->any("/api/{api_key}", function ($api_key) {
    echo 'My API Key: ' . $api_key;
});
```

## Template Engine
the template engine is a tool for generating HTML dynamically from templates. The render method in the template engine takes two parameters: the name of the template file and an associative array of variables to be used within the template. The template file should contain HTML markup with placeholders for the variables, which are replaced with their corresponding values when the template is rendered. The example below demonstrates how to use the render method to generate HTML for a page with a custom title:
```
$template->render('inc/views/page/index/home.php', ['title' => 'My Home Page']);
```

```
$route->get("/", function () {
    require_once ('inc/init/index.php');
    $template->render('inc/views/page/index/home.php', ['title' => 'My Home Page']);
});
```

## Classes
The framework comes with an autoloading feature that automatically loads classes as they are used, without the need for explicit include or require statements. To create a new class, all you have to do is create a new file in the `inc/classes/` folder with the same name as the class, and define the class within that file. The autoloading feature will then automatically load the file and make the class available for use.

For example, suppose you want to create a class called MyClass. You would create a file called MyClass.php in the `inc/classes/` folder and define the class within that file. You can then create an instance of the class using the standard syntax:
```
$MyObject = new MyClass();
```


## Database
Dealing with database was never an easy job, so i created a php class for interacting with databases using the PDO (PHP Data Objects) library. This class allows you to easily connect to a database, execute SQL queries, and perform common database operations like SELECT, INSERT, UPDATE, and DELETE.

### Select Statement
```
$result = $database->select('my_table', '*', 'id = :id', ['id' => 1]);
$rows = $result->fetchAll();
```
### Insert Statement
```
$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '555-1234'
];
$result = $database->insert('my_table', $data);
$id = $database->lastInsertId();

```
### Update Statement
```
$data = [
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'phone' => '555-4321'
];
$where = ['id' => 1];
$result = $database->update('my_table', $data, $where);
```
### Delete Statement
```
$where = 'id = :id';
$bind = ['id' => 1];
$result = $database->delete('my_table', $where, $bind);
```
### Query
if you have a complex query that you want to perform you can use the `query` method instead of the mentioned one
```
// Define the SQL query
$sql = "SELECT * FROM `users` LEFT JOIN `posts` ON posts.user_id = users.user_id WHERE id = :id ";

// Define the parameter values
$parameters = [':id' => 123];

// Execute the query and fetch the results
$result = $database->query($sql, $parameters)->fetch();
```

## User Defined Functions
### Security
the framwork comes with security features that enables you to secure you application for both front-end and back-end.
* to prevent SQL Injection, you must always use the database class that provided in the previous example
* to prevent Cross-Site Scripting (XSS) when always use the `user defined function` that provided
```
$secure_input($username);
OR
$secure_output($username);
```
### Formating
Formatting refers to the process of converting data from one form to another, often to make it more easily readable or understandable by humans. In programming, formatting functions are often used to convert raw data into a more user-friendly format.

we have two formatting functions: `format_date` and `format_size`. format_date accepts a datetime value and returns it in a specified format, while format_size accepts a size value (such as a file size) and converts it to a human-readable format (such as "1.5 MB").

It's also important to note that these formatting functions can help improve the security of your application. For example, if you are displaying dates or sizes in a user interface, you want to make sure that any user input is properly sanitized to prevent SQL injection attacks. By using a formatting function like format_date or format_size, you can ensure that any user input is properly formatted and sanitized, reducing the risk of security vulnerabilities.
### Others
* The `redirect()` function is used to redirect the user to a different URL. It sends an HTTP header to the client to instruct the browser to request the specified URL.
* `get_ip()` function retrieves the client's IP address, even if they are behind a proxy.
* `generate_random_string() a function that generate random letters and numbers
