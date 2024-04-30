# Documentation
## Class loader
The class loader used to import a single or all classes within a file or a directory. To do that, use
```php
$classLoader = ClassLoader::getInstance();
$classLoader->loadClass("FILE");
$classLoader->loadClasses("DIRECTORY", []);
```
<b>Note:</b> When loading all classes within a directory, all files within the exceptions array will be ignored.
