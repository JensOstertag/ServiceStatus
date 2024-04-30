# Documentation
## Logger
A helpful tool for developing and maintaining a project with the framework is its logger. It allows you to easily write info, error or debug messages into a logfile. To do that, use
```php
Logger::getLogger("NAME")->info("MESSAGE");
Logger::getLogger("NAME")->error("MESSAGE");
Logger::getLogger("NAME")->debug("MESSAGE");
```
> <b>Note:</b> The logger is only writing a message to the logfile if it's importance is higher than specified in the logger config under `LOG_LEVEL`.

There are three different log levels:
- `DEBUG`
- `INFO`
- `ERROR`
 
The minimum required log level for a message to be written in the logfile can be set in the `📄 project/config/config.php` file. The default value is `INFO`. You can also change the directory where logfiles should be saved in as well as their filename format there. By default, there is one logfile per day.

