YamlConfigServiceProvider for Silex 2
=

This is a Service Provider that permits to use YAML, JSON and PHP config files in a Silex app.

Requirements
-
  - silex/silex: 2.X

Suggested
-
  - symfony/yaml: 3.X

# Installation


Install with Composer:

    $ composer require "hello-motto/config-service-provider": "dev-master"

Or add to composer.json

``` json
"require": {
    "hello-motto/yaml-config-service-provider": "dev-master"
}
```

# Usage
This Provider will parse your YAML, JSON and PHP files to set config variables.
Thoses config variables are available through `$app['config']['myVariable']`.
Some special variables you can define (parameters variable) also are available through `$app['parameters']`.
All the variables that are contained in the several config files are recursively merged in a array.

##Add files

You can pass one or several files to the ServiceProvider in the 'config.files' argument. No matter if it's a json, yaml or php file

``` php
$app->register(new HelloMotto\Silex\Config\ConfigServiceProvider(), [
    'config.files' => [
        PATH_TO_FIRST_FILE,
        PATH_TO_SECOND_FILE,
        PATH_TO_THIRD_FILE,
        etc.
    ]
]);
```

where `PATH_TO_CONFIG_FILE` is location of YML, JSON or PHP file with configuration for example
```php
__DIR__. "/config/config.yml",
__DIR__. "/config/config.json,
__DIR__. "/config/config.php`,
```

### Files example :
**config/config.php**
```php
<?php
return [
    'parameters' => [
        'dbhost' => 'localhost',
        'dbuser' => 'user',
        'dbpass' => 't@rt1fl3tt3',
        'dbport' => '~' // tilde will be replaced by null value
    ],
    'twig' => [
        'twig.form.templates' => [
            'formage.html.twig'
        ]
    ]
];
```
**config/config.json**
```json
{
    "parameters": {
        "dbhost": "localhost",
        "dbuser": "user",
        "dbpass": "t@rt1fl3tt3",
        "dbport": "~" // tilde will be replaced by null value
    },
    "twig": {
        "twig.form.templates": [
            "formage.html.twig"
        ]
    }
}
```
**config/config.yml**
```yaml
parameters:
    dbhost: "localhost"
    dbuser: "user"
    dbpass: "t@rt1fl3tt3"
    dbport: "~" #tilde will be replaced by null value
twig:
    twig.form.templates:
        -"formage.html.twig"
```
##Add constants

You can also pass to the ServiceProvider constants parameters to replace in your config files.
It permits to use the PHP functions within the configuration files.
Constants name must begin and end with % character.
You can set the constants in the 'config.constants' array :
```php
$app->register(new HelloMotto\Silex\Config\ConfigServiceProvider(), [
    'config.files' => [],
    'config.constants' => [
        '%web.dir%' => '__DIR__.'/../web',
        '%need%' => 'tartiflette',
        etc.
    ]
]);
```

**config/config.json**
``` json
{
    "mySubDirectory": "%web.dir%/reblochon",
    "our_motto": "In %need% we trust"
}
```

**config/config.yml**
``` yaml
    mySubDirectory: "%web.dir%/reblochon",
    our_motto: "In %need% we trust"
```

The `app['config']['mySubDirectory']` variable will contain `In tartiflette we trust`.

##Add closures

YAML and JSON files don't allow to use dynamic code.
But some Providers like Security Provider are more powerful with closure parameters.
That's why it is possible to add some closures with the 'config.closures' parameter.
To use the closure parameter, your array must have the same tree structure.

```php
$app->register(new HelloMotto\Silex\Config\ConfigServiceProvider(), [
    'config.files' => [],
    'config.constants' => [],
    'config.closures' => [
        'security' => [
            'security.firewalls' => [
                'main' => [
                    'users' => new UserProvider()
                ]
            ]
        ]
    ]
]);
```

##Import files
It's possible to import files from a YAML or JSON config file.
You just need to use the object "import" and set an array of resources.
The files are always imported with a relative path from the current file.
Notice that PHP config files don't support the import method.

**config/config_dev.json**
``` json
{
    "imports": [
        {
          "resource": "config.json"
        },
        {
          "resource": "parameters.json"
        }
    ]
// config.json and parameters.json are in the same directory as config_dev.json
}
```

**config/config_dev.yml**
``` yaml
imports:
    - { resource: config.yml }
    - { resource: parameters.yml }
# config.yml and parameters.yml are in the same directory as config_dev.yml
```


#Traits

###config() method
Adding this trait to your Application, you can use the config variables as object.
`$app['config']` will be available using `$app->config()`.
You can also access to the first dimension of the array passing the parameter to the method.
`$app['config']['twig']` is available through `$app->config('twig')`.

###parameters() method
This shortcut also exists for the parameters variables. `$app['parameters']['dbhost']` will be the same that `$app->parameters('dbhost')`

###loadFile() method
This shortcut use the ConfigLoader to parse a JSON, YAML or PHP file into an array, replacing the constants that are already set.

###loadManyFiles() method
This method does the same as loadFile() with an array of files as argument.


# Licence
[GPL 3.0](https://www.gnu.org/licenses/gpl-3.0.html)

# Message from the President
[Beef...](https://youtu.be/M2wyG8Kt3fA)