# Documentation
## Config
The framework provides a configuration file that allows you to configure the functions of the framework and your project.

The configuration file (`app-config.php`) is located in the `📁 project/config/` directory. It includes the `📄 secrets/config.secret.json` file, where settings that should be ignored in git repositories (e.g. database credentials, parameters that are changed for testing purposes, ...) can be overwritten.

You can modify the following settings:
- Router settings
  - `ROUTER_BASE_URI` - The base URI of the project (e.g. if the project is hosted as a subdirectory of a domain, the base URI is that subdirectory)
- Project settings
    - `PROJECT_NAME` - The project's name that is displayed in the header and footer
    - `WEBSITE_TITLE` - The title that is displayed for the browser tab
    - `PROJECT_URL` - The project's URL
    - `PROJECT_FAVICON` - URL to the projects favicon
    - `PROJECT_AUTHOR` - The author of the project that is displayed in the footer
    - `PROJECT_VERSION` - The version of the project that is displayed in the footer
- Menu settings
    - `MENU_SIDEBAR` - The items shown in the sidebar navigator
- Time format settings
    - `DATE_TECHNICAL` - The format of a date that is used by the backend
    - `TIME_TECHNICAL` - The format of a time that is used by the backend
    - `DATETIME_TECHNICAL` - The format of a date and time that is used by the backend
    - `DATE_VISUAL` - The format how a date is displayed in the frontend
    - `TIME_VISUAL` - The format how a time is displayed in the frontend
    - `DATETIME_VISUAL` - The format how a date and time is displayed in the frontend
- Logger settings
    - `LOG_DIRECTORY` - The directory where the logfiles should be stored
    - `LOG_FILENAME` - The filename format of a logfile
    - `LOG_LEVEL` - The minimum importance that is required for a message to get written into a logfile
- Database settings
    - `DB_HOST` - Database hostname
    - `DB_USER` - Database username
    - `DB_PASS` - Database password
    - `DB_NAME` - Database name
    - `DB_USE` - Whether the database connection should be established upon app start
- Mail settings
    - `MAIL_DEFAULT_SENDER_EMAIL` - The default sender email address
    - `MAIL_DEFAULT_SENDER_NAME` - The default sender name
    - `MAIL_DEFAULT_REPLY_TO` - The default reply-to email address
    - `MAIL_DEFAULT_SUBJECT` - The default subject of an email
    - `MAIL_REDIRECT_ALL_MAILS` - Whether all mails should be redirected to a specific email address for testing purposes
    - `MAIL_REDIRECT_ALL_MAILS_TO` - The email address to which all mails should be redirected (if `MAIL_REDIRECT_ALL_MAILS` is set to `true`)
- Class Loader settings
    - `CLASS_LOADER_IGNORE_FILES` - Files that should be ignored by the class loader
    - `CLASS_LOADER_IMPORT_PATHS` - Paths that should be imported by the class loader
- SEO settings
    - `SEO_DEFAULT_DESCRIPTION` - The default description of the website
    - `SEO_KEYWORDS` - The keywords of the website
    - `SEO_IMAGE_PREVIEW` - The image that is shown when the website is shared on social media platforms. The setting is used by OpenGraph and Twitter meta tags.
    - `SEO_OPENGRAPH`
        - `OPENGRAPH_SITE_NAME` - The value that should be used for the `og:site_name` meta tag
    - `SEO_TWITTER`
        - `TWITTER_SITE` - The value that should be used for the `twitter:site` meta tag
        - `TWITTER_CREATOR` - The value that should be used for the `twitter:creator` meta tag
    - `SEO_ROBOTS` - An array of settings for the `robots` meta tag
    - `SEO_REVISIT` - The value that should be used for the `revisit-after` meta tag
