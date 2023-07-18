<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Prozorro test project</h1>
    <br>
</p>

**Project changes:**
- Sensitive data for db config and other env config moved to `config/env_settings.php`
- Added `service` directory

Instructions for changes will be below.

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      services/           contains services for project work
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4.


SETUP
------------

1. Clone project:
    ~~~
    https://github.com/Fandref/prozorro_test_task.git
    ~~~

1. Install dependencies:
    ~~~
    composer install
    ~~~

1. Make migrate:
    ~~~
    yii migrate
    ~~~

    **NOTE:**

    Before run this command complete configuration

1. Launch dev server

    ~~~
    yii serve
    ~~~



CONFIGURATION
-------------

### Env settings

*This is a simple and quick way to solve the problem, of course there are better options for solving this problem, such as extensions `dotenv`.*

The `config/env_settings.php` file should have the following format:

```php
return [
    'env' => 'dev',
    'debug' => false,
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'name' => 'yii2basic',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ],
    'test_db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'name' => 'yii2basic_test',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]
    
];
```

**NOTE:**

Also you can file `config/example.env_settings.php` rename to `config/env_settings.php` and input your data

### Database

In connection with the changes you do not need to change. You can added other params in `config/db.php`, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => "{$env['db']['driver']}:host={$env['db']['host']};dbname={$env['db']['name']}",
    'username' => $env['db']['user'],
    'password' => $env['db']['password'],
    'charset' => $env['db']['charset'],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
