# Documentation
## Date formatter
The ``DateFormatter`` class is used to format or parse DateTimes. The usage is demonstrated with the ``DATETIME_TECHNICAL`` format, but it is the same for all other formats defined in the date format config. These methods ensure that the date format is always the same and as defined in the config, no matter where it is used.
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::technicalDateTime($datetime);
$parsedDate = DateFormatter::parseTechnicalDateTime($formattedDate);
```