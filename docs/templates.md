# Documentation
## Template files
This framework uses template files to separate view and logic, which ensures maintainability of the project. Specifically, it uses the [Templify library](https://github.com/JensOstertag/php-templify), which considers template files to be normal PHP files that contain the static HTML and dynamic PHP content of the website. The files are located in the ``📁 project/htdocs/frontend/`` directory and are included by the scripts in the ``📁 project/htdocs/`` directory.

To learn about how to use the templating engine, please refer to the [Templify documentation](https://github.com/JensOstertag/php-templify).

Because the Templify config is an array, it allows to set custom values which comes in handy when you want to set different website titles for different pages whilst still reusing the same header template. To set the website's title, use
```php
Templify::setConfig("WEBSITE_TITLE", "Title");
```
where ``Title`` is the title that should be displayed in the browsers tab. This value will be prepended to the default website title specified in the project config.
