<div align="center">

<!--![Header](docs/img/header.jpg)-->

# PHP-Framework

### Powerful and feature-rich PHP framework designed to simplify web development

Built-in support for data access and manipulation, routing and various other utilities make it easier to handle common web development tasks.

[Introduction](#introduction) • [Project setup](#project-setup) • [Documentation](#documentation) • [Dependencies](#dependencies) • [License](#license)

</div>

<hr>

## Introduction

This framework is designed to simplify web development by providing a scalable architecture and a set of useful features that are often needed when developing web applications. The most notable features are:
- **GitHub Actions pipeline** to automatically build, test and deploy the application
- The **router** which allows to define specific routes for the website
- **Template files** to strictly separate logic from view - as intended by the MVC pattern
- The **data access object pattern** allows to easily access and manipulate data in the database by using objects (and inheritance)
- A **safe** and **ready-to-use** user management (and login) system which can also handle multiple account types
- Methods which help to **design RESTful APIs**
- **Info messages** that provide a simple way to display info, warning, error and success messages to the user from PHP and JavaScript
- Accessible **SEO settings** which define how the website should be displayed by search engines and social media platforms

### Deployment options
Applications build with this framework can be deployed either on an Apache web server or in form of a Docker container which runs a nginx web server.

### Prerequisites
When deploying the application on an Apache web server, the following prerequisites have to be met:
- PHP 8.2 or higher
- MySQL or MariaDB database (or no database if it's not required by the application)
  - When deploying the application with Docker, the database is already configured in the Docker Compose infrastructure

If you want to deploy it as a Docker container instead, the following requirements hold:
- Docker and Docker Compose need to be installed
- The container has to be accessible from outside (e.g. by using a reverse proxy)
- Other limitations that depend on the server infrastructure may occur (e.g. sending emails by the default `mail` method is currently not possible) and have to be fixed manually (please share your learnings about these problems and your fixes)

## Project setup

### Repository setup
- Use this repository as a template for your project by clicking on the green button `Use this template` on the top of this page. This will create a new repository with the same files as this one.
- Under the repository's `Settings` > `Actions` > `General`, change the workflow permissions from "Read repository contents and packages permissions" to "Read and write permissions" to allow the pipeline to add the newly built package to the GitHub Container Registry.
- Create repository variables and secrets for GitHub Actions:

  | Variable name          | Description                                                         |
  |------------------------|---------------------------------------------------------------------|
  | `AUTODEPLOY_ACTIVATED` | Whether or not the automatic deployment feature should be activated |
  | `AUTODEPLOY_BASE_URL`  | The base URL of the website                                         |

  | Secret name                | Description                                                                                          |
  |----------------------------|------------------------------------------------------------------------------------------------------|
  | `AUTODEPLOY_AUTH_USERNAME` | The username that is used to authenticate when calling the website (if not required, leave it empty) |
  | `AUTODEPLOY_AUTH_PASSWORD` | The password that is used to authenticate when calling the website (if not required, leave it empty) |

### Local setup
- Clone the newly created repository onto your local machine.
- Configure the `📄 composer.json` file according to your needs: Change the project's name, description and license, and add required dependencies.
- Configure `📄 project/config/app-config.php` according to your needs, except for secrets such as database credentials. These are treated in separate configuration files.
- Configure `📄 secrets/config.secret.json.example` with the same values as in the previous step, except for actual secrets such as database credentials.
- Copy `📄 secrets/config.secret.json.example` to `📄 secrets/config.secret.json` and configure it according to your needs. This file is ignored by the `.gitignore` file and therefore not included in the repository. Also store secrets such as database credentials in this file. It's recommended to remove all fields that are already defined in `📄 project/config/app-config.php` from this file.
- Configure `📄 htdocs/deployment/deploy-config.json` according to your needs.
- Commit and push the changes to the repository.

### Deployment setup - Apache web server
The application can be deployed on an Apache web server, which allows you to use the automatic deployment feature. When pushing your changes to the `main` branch, the GitHub Actions pipeline will call the `/deploy` route. This wil execute a `git pull` command to pull your new changes, and install the required dependencies through Composer. To use this feature, follow these steps:
- Clone the newly created repository onto the web server, e.g. to `/var/www/your-project-name`. This should be done as the web server user: When the `/deploy` route is called, the `git pull` command will always be executed by the web server user, and in order to override the files, he needs read, write and delete permissions. Alternatively, you can set the files' owner afterwards through `chown` command.
- Copy `📄 secrets/config.secret.json` from your local setup to the `📁 secrets/` directory on the server.
- Run `composer install` within the repository directory to install the required dependencies. When deploying the application through the pipeline, this step will be executed automatically.
- Set up the virtual host for the website. The `DocumentRoot` should point to the directory where you've cloned the repository to, and then `📁 htdocs/` <sub>Not `📁 project/htdocs/`!</sub>. If you've used the example path from above, the `DocumentRoot` should be set to `/var/www/your-project-name/htdocs`.

### Deployment setup - Docker container
- Create and configure the `📄 docker-compose.yml` file according to your requirements. The easiest way to do this is by simply cloning the newly created repository onto the server that the Docker container should run on, and then configuring it.
  - Change the `image` name / link
  - Change the access credentials for the database
- Copy `📄 secrets/config.secret.json` from your local setup to the `📁 secrets/` directory on the server, or configure the file directly on the server, depending on your needs.
  - Change the database access credentials, as configured in the `📄 docker-compose.yml` file
- Run `docker-compose up -d` to start the container. This will pull the image from the GitHub Container Registry and start the container.

## Documentation

### First time using the framework?
Please take a look at the documentation of the [file structure](docs/file-structure.md), the [config](docs/config.md) and the [logger](docs/logger.md) first.

### Tutorials
This section provides quick tutorials about how to use the framework and its features.

<details>
<summary><b>Using the logger</b></summary>

It's helpful to use the logger to understand what's going on, whilst developing the project as well as in production. The logger is used to log messages with different log levels. The log levels are the following:
- `DEBUG`
- `INFO`
- `ERROR`

You can set the minimum required log level for a message to be written in the logfile in the `📄 project/config/config.php` file. The default value is `INFO`. You can also change the directory where logfiles should be saved in as well as their filename format there. By default, there is one logfile per day.

In the following example you can see how the logger should be used:
```php
<?php
Logger::getLogger("TAG")->debug("MESSAGE");
Logger::getLogger("TAG")->info("MESSAGE");
Logger::getLogger("TAG")->error("MESSAGE");
```
</details>

<details>
<summary><b>Create a new website page</b></summary>

To create a new page for the website, create a new PHP file that should get executed when the user visits the page. The file should be located in the `📁 project/htdocs/` directory. There are no limitations what you can do in the script, but it's not recommended to output HTML code or other content directly (exception: you want to send JSON responses, please take a look at the corresponding tutorial or the [`Comm` class documentation](/docs/comm.md)). 

Instead, to output content, create a PHP template file in the `📁 project/frontend/` directory. A template file is a normal PHP File that is specialized for outputting content. This separation not only takes care of a better overview, it also separates the logic from the view. For further information about template files, please take a look at the [template documentation](/docs/template.md) or the <a href="https://github.com/JensOstertag/php-templify">Templify documentation</a>.

Have a look at the following example:

`📄 project/htdocs/example.php`:
```php
<?php

use jensostertag\Templify\Templify;

// Assign a variable
$variable = "Hello World!";

// Load the template
Templify::display("example.php", ["variable" => $variable]);
```
This file is the script that gets executed when the user visits the page. In this example, a variable is assigned and the template `example.php` is loaded.

`📄 project/frontend/example.php`:
```php
<?php
use jensostertag\Templify\Templify;

// Set the website title and include the header template
Templify::setConfig("WEBSITE_TITLE", "Title");
Templify::include("header.php");
?>

<?php output($variable); ?>

<?php
use jensostertag\Templify\Templify;

// Include the footer template
Templify::include("footer.php");
?>
```
This is the template file. It sets a website title and includes the header and footer template files located in the `📁 project/htdocs/frontend/includes/` directory. The variable that was assigned in the script is then outputted. The `output` function is a helper function that is used to output content. It takes care of escaping HTML characters. If there is a need to output unescaped content, the default `echo` function can be used instead.

> <b>Note:</b> You could also use [PHP short tags](https://www.php.net/manual/en/language.basic-syntax.phptags.php) within the template file, but make sure that they are enabled in your PHP configuration before doing so.

To learn how to set up a route for your newly created page, take a look at the next tutorial.
</details>

<details>
<summary><b>Create a route for a page</b></summary>

To create a route for a page, you have to add a new entry to the `📄 project/config/app-routes.php` file. In that file you can already see some examples that use the `Router::addRoute` method to register a new route. The function takes the following parameters:
- `$method` - The HTTP method(s) that should be allowed to access the route<br>
  Multiple methods can be specified by separating them with a pipe (`|`) character. For example: `GET|POST`
- `$route` - The URI that should be used to access the route<br>
  GET parameters can be added to the route by using the following syntax: `{type:name}`<br>
  Supported types are `b` (boolean), `d` (date (without time)), `f` (float), `i` (integer), and `s` (string).<br>
  The name of the parameter is used to identify the parameter within the `$_GET` array.

Assumed you already have a script called `📄 example.php` in the `📁 project/htdocs/` directory (as described in the previous tutorial about setting up a new page), you can use the following code example to add a route for that page:
```php
Router::addRoute("GET|POST", "/example", "example.php", "example");
```
This will allow you to access the page with the URI `/example` with either the `GET` or `POST` method.

Let's have a look at a more complex example: Assumed you've had a script (`api.php`) that represents an API call that requires passing a `GET` parameter called `id` of the integer type. You can add a route for this script as shown in the following code:
```php
Router::addRoute("GET", "/api/{i:id}", "api.php", "api");
```
This will allow you to access the page by the URI `/api/ID` with `ID` being the integer value of the `id` parameter of the `GET` method. The `id` parameter can then be accessed within the `📄 api.php` script by using the `$_GET` array like this:
```php
$id = $_GET["id"];
```
For more information about the `Router` class, take a look at the [router documentation](docs/router.md).
</details>

<details>
<summary><b>Create a sidebar menu item for a page</b></summary>

The items in the sidebar menu are defined in the `📄 project/config/app-config.php` file in the `$MENU_SETTINGS["MENU_SIDEBAR"]` array with the following structure:
```php
[
    "DISPLAY_NAME" => [
        "route" => "ROUTE"
    ],
    // ...
]
```
The `DISPLAY_NAME` is the name that is displayed in the sidebar menu. The `ROUTE` is the URI that is used to access the page. It's recommended to use the `Router::generate` method to generate the URI automatically. Have a look at the [router documentation](docs/router.md) for more information.

Assumed you've already created a page and a route with the route name `example` to that page (as it was described in the previous tutorial), you can add a sidebar menu item as follows:
```php
Config::$MENU_SETTINGS["MENU_SIDEBAR"]["Example"] = [
    "route" => Router::generate("example")
];
```
</details>

<details>
<summary><b>Create a new object that can be stored in the database</b></summary>

Creating and modifying entries in the database is done automatically by the implemented data-access-object (DAO) pattern. 

There are so-called "model objects" that represent the data that's being stored in the database, and for each table there is an own model object. They are located in the `📁 project/src/object/` directory. 

There's also a data access object interface that defines the standard operations that can be performed on the model objects, such as creating, reading and updating entries. For every model object, there is an own belonging data access object that's located in the `📁 project/src/dao/` directory.

To prevent you from having to write the same code over and over again, there are classes called `GenericObject` (model object) and `GenericObjectDAO` (data access object interface) that every custom object should extend from. The `GenericObject` class already implements the table columns
- `id` (integer) - The unique identifier of the object
- `created` (datetime) - The date and time when the object was created
- `updated` (datetime) - The date and time when the object was last updated

and the `GenericObjectDAO` the standard operations
- `GenericObjectDAO::save(GenericObject $object)` to create or update an object's database entry
- `GenericObjectDAO::getObject(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` to get a single object from the database
- `GenericObjectDAO::getObjects(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` to get multiple objects from the database

Assumed you want to create a new database table called `Example` with the following columns:

| <i>`id`</i> | `myAttribute` | <i>`created`</i> | <i>`updated`</i> |
|-------------|---------------|------------------|------------------|
| integer     | varchar       | datetime         | datetime         |

> <b>Note:</b> The columns `id`, `created` and `updated` are already implemented in the `GenericObject` class and don't need to be defined in the custom object, but are required for the database table.

At first, you need to create the table manually as this is not done automatically:
```sql
CREATE TABLE IF NOT EXISTS `Example` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `myAttribute` VARCHAR(255) NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
> There is a file `📄 project/src/schema/tables.sql` where you can document the SQL statements that are required for your project. This allows you or other persons to easily recreate tables in case you want to set up another instance of your project in the future.

Next, you have to create a class called `Example` that extends the `GenericObject` class for the model object in the `📁 project/src/object/` directory with public attributes named after the exact column names and getter and setter methods to manipulate the data. It's also important to name the class the same as your database table.

`📄 project/src/object/Example.class.php`
```php
<?php
class Example extends GenericObject {
    public string $myAttribute;

    public function getMyAttribute(): string {
        return $this->myAttribute;
    }

    public function setMyAttribute(string $myAttribute): void {
        $this->myAttribute = $myAttribute
    }
}
```

Finally, you have to add the class `ExampleDAO` that extends the `GenericObjectDAO` class in the `📁 project/src/dao/` directory. Here, it's also important to name the class the same as your database table concatenated with `DAO`. 

`📄 project/src/dao/ExampleDAO.class.php`
```php
<?php
class ExampleDAO extends GenericObjectDAO {

}
```

For the plain usage of the DAO pattern you don't need to add custom methods as they are already implemented by the `GenericObjectDAO`. However, if you need methods with custom SQL queries or statements, you should add them in this DAO class.
</details>

<details>
<summary><b>Using data access objects</b></summary>

To create a new database entry for an object, you have to create an instance of the model object, set its attributes (except for the `id` attribute) and save it with the corresponding DAO's `GenericObjectDAO::save(GenericObject $object)` method. The `id` attribute will be set automatically by the database.

The following example shows how to save a new `Example` object that was described in the previous tutorial:
```php
// Create a new instance of the Example object
$example = new Example();

// Set the attributes
$example->setMyAttribute("Hello World!");

// Save the object to the database
Example::dao()->save($example);
```

To retrieve objects from the database, you can use the belonging DAO's `GenericObjectDAO::getObject(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` or `GenericObjectDAO::getObjects(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` method. The difference between both is that the first onne returns a single object and the second one returns an array of found objects. The parameters are the same for both methods:
- `filters`: An array which contains requirements that the returned objects should meet
- `orderBy`: A column name that the returned objects should be ordered by
- `orderAsc`: Whether the returned objects should be ordered ascending or descending
- `limit`: The maximum amount of objects that should be returned (-1 for no limit)
- `offset`: The offset from which the objects should be returned

Have a look at the following code examples:
```php
// Get all objects
$examples = Example::dao()->getObjects();

// Get the first object that has the attribute "myAttribute" set to "Hello World!"
$example = Example::dao()->getObject([
    "myAttribute" => "Hello World!"
]);

// Get the object with the id 42
$example = Example::dao()->getObject([
    "id" => 42
]);

// Get the first 10 objects that have the attribute "myAttribute" set to "Hello World!"
$examples = Example::dao()->getObjects([
    "myAttribute" => "Hello World!"
], "id", true, 10, 0);
    
// Get the first 10 objects that have the attribute "myAttribute" set to "Hello World!" in descending order by the attribute "myAttribute"
$examples = Example::dao()->getObjects([
    "myAttribute" => "Hello World!"
], "myAttribute", false, 10, 0);
```

Filters can also be more complex by combining multiple requirements (the combination of multiple requirements is always to be seen as a logical AND), or by using other operators than the equality check:
```php
$lastWeek = (new DateTime())->modify("-7 day");

// Get all objects that have the attribute "myAttribute" set to "Hello World!" and that were created in the last week
$examples = Example::dao()->getObjects([
    "myAttribute" => "Hello World!",
    [
        "field" => "created",
        "filterType" => DAOFilterType::GREATER_THAN_EQUALS,
        "filterValue" => $lastWeek
    ]
]);
```

This shows well that the key-value pairs that we have used previously are short terms for
```php
[
    "field" => "myAttribute",
    "filterType" => DAOFilterType::EQUALS,
    "filterValue" => "Hello World!"
]
```

There are the following filter types:
- `DAOFilterType::EQUALS` - The fields value should be equal to the filter value
- `DAOFilterType::NOT_EQUALS` - The fields value should not be equal to the filter value
- `DAOFilterType::GREATER_THAN` - The fields value should be greater than the filter value
- `DAOFilterType::GREATER_THAN_EQUALS` - The fields value should be greater than or equal to the filter value
- `DAOFilterType::LESS_THAN` - The fields value should be less than the filter value
- `DAOFilterType::LESS_THAN_EQUALS` - The fields value should be less than or equal to the filter value
- `DAOFilterType::LIKE` - The fields value should be like the filter value
- `DAOFilterType::IN` - The fields value should be in an array of values
- `DAOFilterType::NOT_IN` - The fields value should not be in an array of values

so also non-trivial SQL queries can be expressed with the DAO pattern.

To update an object, you have to retrieve it from the database first and then update its attributes with the setter methods. After that, you can save the object again with the `GenericObjectDAO::save(GenericObject $object)` method. As it is the same object with a set value for the `id` attribute, the DAO will update the existing entry instead of creating a new one.
```php
// Get the object with the id 42
$example = Example::dao()->getObject([
    "id" => 42
]);

// Update the attribute
$example->setMyAttribute("Another value");

// Save the object to the database
Example::dao()->save($example);
```
> <b>Note:</b> The `updated` attribute is not changed automatically.

To delete an object, you have to retrieve it from the database first and then call the `GenericObjectDAO::delete(GenericObject $object)` method which will physically delete the entry from the database.
```php
<?php
// Get the object with the id 42
$example = Example::dao()->getObject([
    "id" => 42
]);

// Delete the object
Example::dao()->delete($example);
```

For more information about the DAO pattern, make sure to read the [DAO documentation](docs/dao-pattern.md).
</details>

<details>
<summary><b>Redirect to other pages or websites</b></summary>

To redirect the user to another page or website, you can use the `Comm` class' `Comm::redirect(String $path)` method. It sets the `Location` header to the given path and stops the execution of the script.

The following example shows how to redirect to another page of your website:
```php
Comm::redirect(Router::generate("ROUTE"));
```
It uses the `Router::generate(String $route)` method to generate the path to the given route automatically. Have a look at the [router documentation](docs/router.md) for more information.

To redirect to another website, you can use the following code example:
```php
Comm::redirect("https://www.example.com");
```

</details>

<details>
<summary><b>Send JSON responses</b></summary>

You can send the user an individual JSON response by using
```php
Comm::sendJson(DATA);
```
with `DATA` being an array that should be JSON-encoded.

If you're developing an API, you might want to send a status code and message along with the result. This can be achieved easily by using
```php
Comm::apiSendJson(RESPONSE, DATA);
```
Both `RESPONSE` AND `DATA` are arrays. `DATA` holds the main content that should be returned. `RESPONSE` holds the status code and message and should be formatted as shown in the following scheme:
```php
[
    "code" => STATUS_CODE,
    "message" => "STATUS_MESSAGE"
]
```
There are predefined response arrays located in the `HTTPResponses` class. It contains the most relevant HTTP responses (`200`, `201`, `204`, `400`, `401`, `403`, `404`, `405`, `500`, `501`, `503`) that are also common to occur in API usage.

The returned JSON response will look like this:
```json
{
    "code": STATUS_CODE,
    "message": "STATUS_MESSAGE",
    "data": DATA
}
```
> <b>Note:</b> The `data` field is a JSON object if the passed `DATA` array is an associative array and a JSON array if it is a sequential array.

You can use those responses with `apiSendJson` like shown in the following example:
```php
$userDAO = User::dao();
Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, $userDAO->getObjects());
```
This will return roughly the following response (`data` will hold the information about all users of course):
```json
{
    "code": 200,
    "message": "OK",
    "data": [
        ...
    ]
}
```
Because `$userDAO->getObjects()` returns a sequential array, the `data` field in the JSON response is a JSON array.<br>
<sub>
> <b>Note:</b> You wouldn't want this to be a real API call since it will return <b>ALL</b> information about <b>EVERY</b> user from the database such as real names, password hashes, ...
</sub>

For more information, have a look at the [`Comm` class documentation](docs/comm.md). 
</details>

<details>
<summary><b>Display info, warning, error or success messages</b></summary>

To display info, warning, error or success messages, you can use the `InfoMessage` class. It's constructor takes a parameter for the message itself and the message type. The message type is an integer that is defined as static values of the `InfoMessage` class.

This is how you can display an info message:
```php
// Info message
new InfoMessage("This is an info message", InfoMessageType::INFO);

// Warning message
new InfoMessage("This is a warning message", InfoMessageType::WARNING);

// Error message
new InfoMessage("This is an error message", InfoMessageType::ERROR);

// Success message
new InfoMessage("This is a success message", InfoMessageType::SUCCESS);
```
To prevent unwanted side effects, it's recommended to only send info messages from an executed website script.

You might also want to display info messages within JavaScript code. This can be done with the JavaScript `InfoMessage` class. Its usage is almost the same as the one in PHP:
```javascript
// Info message
new InfoMessage("This is an info message", InfoMessage.INFO);

// Warning message
new InfoMessage("This is a warning message", InfoMessage.WARNING);

// Error message
new InfoMessage("This is an error message", InfoMessage.ERROR);

// Success message
new InfoMessage("This is a success message", InfoMessage.SUCCESS);
```

As you can modify the DOM within JavaScript, you can also delete / hide info messages. The following example shows how you can delete all info messages displayed by the backend:
```javascript
$(document).ready(() => {
    InfoMessage.clearMessages();
});
```

For more information about the `InfoMessage` class, take a look at the [info message documentation](docs/info-messages.md).
</details>

<details>
<summary><b>Sending cURL requests</b></summary>

You can use the [PHP-Curl](https://github.com/JensOstertag/php-curl) libraries `Curl` class to send HTTP GET or POST requests to other servers. The library is a wrapper for PHPs cURL methods. 

The following example code shows how to send a GET request to read an HTML page:
```php
use jensostertag\Curl\Curl;

$curl = new Curl();

// Define the request and headers
$curl->setUrl("URL");
$curl->setMethod(Curl::$METHOD_GET);
$curl->addHeader([
    "accept" => "text/html, application/xhtml+xml"
]);

// Get the response
$response = $curl->execute();
$responseCode = $curl->getHttpCode();
$curl->close();
```
with `URL` being the URL of the server that you want to send the request to.

As a more complex example, let's assume you want to send a POST request to a server that requires a data body. You can do that as follows:
```php
use jensostertag\Curl\Curl;

$curl = new Curl();

// Define the request and headers
$curl->setUrl("URL");
$curl->setMethod(Curl::$METHOD_POST);
$curl->addHeader([
    "accept" => "application/json"
]);

// Add data to the request
$curl->addPostData([
    "key" => "value"
]);

// Get the response
$response = $curl->execute();
$responseCode = $curl->getHttpCode();
$curl->close();
```
Here, the `URL` is also replaced by the URL of the server that you want to send the request to. 
</details>

<details>
<summary><b>Using the mail helper class</b></summary>

You can use the `Mail` class to send emails. To do that, use
```php
// Initialize the mail object
$mail = new Mail();
$mail->setSenderEmail("SENDER_EMAIL");
$mail->setSenderName("SENDER_NAME");
$mail->setReplyTo("REPLY_TO");
$mail->setSubject("SUBJECT");
$mail->setMessage("MESSAGE");

// Send the mail either as plain text or as HTML
$mail->sendTextMail("RECIPIENT_EMAIL");
$mail->sendHTMLMail("RECIPIENT_EMAIL");
```
with `SENDER_EMAIL` being the displayed sender email address, `SENDER_NAME` the displayed sender name, `REPLY_TO` the email address that should be used as reply-to address, `SUBJECT` the mail's subject, `MESSAGE` it's body and `RECIPIENT_EMAIL` the email address the mail should be sent to.

The mail's body can be formatted as plain text or as HTML. To send it as plain text, use the `sendTextMail` method, if you want to send an HTML mail, use the `sendHTMLMail` method. The difference between both methods is that the `sendHTMLMail` method will add the `Content-Type: text/html` header to the mail.
</details>

<details>
<summary><b>Using the geocoding helper class</b></summary>

> <b>Legal Note:</b> This library uses the [Nominatim](https://nominatim.org/) API. Please read the [Terms of Use](https://operations.osmfoundation.org/policies/nominatim/) before using it and comply with them.

You can use the [PHP-Geocoding](https://github.com/JensOstertag/php-geocoding) library's `Geocoding` class to convert an address to coordinates or coordinates to an address. The library uses the [Nominatim](https://nominatim.org/) API to do that.
```php
// Get coordinates of an address
$geocoding = new Geocoding();
$geocoding->setStreet("Street");
$geocoding->setHouseNumber("House number");
$geocoding->setCity("City");
$geocoding->setZipCode("ZIP code");
$geocoding->setCountry("Country");
$coordinates = $geocoding->getCoordinates();
$lat = $coordinates["latitude"];
$lng = $coordinates["longitude"];

// Get address of coordinates
$geocoding = new Geocoding();
$geocoding->setCoordinates(12.345678, 12.345678);
$address = $geocoding->getAddress();
$street = $address["street"];
$houseNumber = $address["houseNumber"];
$city = $address["city"];
$zipCode = $address["zipCode"];
$country = $address["country"];
$formattedAddress = $geocoding->getFormattedAddress();
```
</details>

<details>
<summary><b>Format and parse datetimes</b></summary>

To ensure uniformity, there is a class called `DateTimeFormatter` that can be used to format and parse datetimes. The used format can be changed in the `📄 project/config/app-config.php` file.

To format a datetime to display the current date and time in the frontend, you can use the following code:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::visualDateTime($datetime);
```

In case you want to format a datetime to a date that should be passed to other components of your project (e.g. a JavaScript file), use the following code:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::technicalDate($datetime);
```

You can also parse a datetime string to a datetime object:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::visualDateTime($datetime);
$newDatetime = DateFormatter::parseVisualDateTime($formattedDate);
```

For an overview of all available methods, please have a look at the [documentation](docs/date-formatter.md).
</details>

<details>
<summary><b>Write unit tests</b></summary>

The framework uses the [Pest](https://pestphp.com/) framework to perform unit tests. The tests are located in the `📁 tests/` directory. They can be executed by running
```sh
composer run test
```
or are automatically executed by the GitHub Action pipeline on every push to the `main` branch.
</details>

<details>
<summary><b>Create and register cronjobs</b></summary>

To create a new cronjob, create a new (PHP) file in the `📁 project/cronjobs/` directory. The file should contain the code that should be executed when the cronjob is called. If you're writing a PHP script, make sure to include the `📄 project/cronjobs/.cronjob-setup.php` file at the beginning of the script, which allows you to use the framework's features.

Automatically registering cronjobs is only possible when deploying the application in form of a Docker container. To register the cronjob, add a new entry to the `📄 project/cronjobs/app-cronjobs.php` file with the crontab syntax:
```
┌───────────── minute (0 - 59)
│ ┌─────────── hour (0 - 23)
│ │ ┌───────── day of the month (1 - 31)
│ │ │ ┌─────── month (1 - 12)
│ │ │ │ ┌───── day of the week (0 - 6) (Sunday to Saturday)
│ │ │ │ │
* * * * * command to be executed
```
</details>

<details>
<summary><b>Deploy the application</b></summary>

Before deploying the application, make sure that you have changed all settings for the production version such as changing the minimum required log level. You might also want to minify the CSS and JS files.

Now, there are two separate ways to deploy the application:

<details>
<summary>Apache webserver and the framework's auto deployment tool</summary>

If you have followed all steps in the [installation](#installation) section, you can simply commit and push your changes to the repository's `main` branch. A GitHub Action will then call the `/deploy` route which will pull all changes from the repository and install the dependencies.
</details>

<details>
<summary>Docker</summary>

Unfortunately, the Docker deployment option doesn't work with the automatic deployment feature (yet). You have to re-pull and restart the container manually.
</details>
</details>

Further information about the framework and its features are available in [the documentation](/docs).

## Dependencies
This framework contains the following dependencies:
- **pest** - GitHub: [pestphp/pest](https://github.com/pestphp/pest), licensed under [MIT license](https://github.com/pestphp/pest/blob/2.x/LICENSE.md)
- **Templify** - GitHub: [JensOstertag/templify](https://github.com/JensOstertag/templify), licensed under [MIT license](https://github.com/JensOstertag/templify/blob/main/LICENSE)
- **Curl-Adapter** - GitHub: [JensOstertag/curl-adapter](https://github.com/JensOstertag/curl-adapter), licensed under [MIT license](https://github.com/JensOstertag/curl-adapter/blob/main/LICENSE-MIT) 
- **GeocodingUtil** - GitHub: [JensOstertag/geocoding-util](https://github.com/JensOstertag/geocoding-util), licensed under [GPL-2.0 license](https://github.com/JensOstertag/geocoding-util/blob/main/LICENSE-GPL2)
- **UploadHelper** - GitHub: [JensOstertag/uploadhelper](https://github.com/JensOstertag/uploadhelper), licensed under [MIT license](https://github.com/JensOstertag/uploadhelper/blob/main/LICENSE-MIT)
- **CSVReader** - GitHub: [JensOstertag/csvreader](https://github.com/JensOstertag/csvreader), licensed under [MIT license](https://github.com/JensOstertag/csvreader/blob/main/LICENSE)

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
