# This is my package Application versioning

[![Latest Version on Packagist](https://img.shields.io/packagist/v/misodrobny/application-versioning.svg?style=flat-square)](https://packagist.org/packages/misodrobny/application-versioning)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/misodrobny/application-versioning/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/misodrobny/application-versioning/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/misodrobny/application-versioning/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/misodrobny/application-versioning/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/misodrobny/application-versioning.svg?style=flat-square)](https://packagist.org/packages/misodrobny/application-versioning)

This package will help to manage version of your application. It will create version.yaml file in root of your project and you can manage version of your application in this file.

For more about application versioning please follow [Semantic Versioning](https://semver.org/)

## Installation

You can install the package via composer:

```bash
composer require misodrobny/application-versioning
```

After installation, you need to run install script which will publish config file and create default version.yaml file.
```bash
php artisan application-versioning:install
```

This is the contents of the published config file:

```php
return [
    'version_file_path' => base_path('version.yaml'),
];
```

Initial formated version has following structure:
```php
$major.$minor.$patch - $git_hash
```
`$git_hash` contains 7 characters long GIT HASH from current git commit. 

## Usage

For getting version of your application you can use facade `ApplicationVersion`
```php
    echo ApplicationVersion::getFormatedVersion();
```

To increase version of your application you can use following methods:
```php
    (new ApplicationVersioning)->increaseMajor();
    (new ApplicationVersioning)->increaseMinor();
    (new ApplicationVersioning)->increasePatch();
```
It is also possible to run 
```bash
php artisan application-version:increase-major 
php artisan application-version:increase-minor 
php artisan application-version:increase-patch 
```

Automatically Retrieve the Current Git Tag

To ensure that your application’s version is automatically updated based on the current Git tag whenever the application boots, you can add a call to the getActualGitTag() method from ApplicationVersioningServiceProvider in the boot() method of the AppServiceProvider.

Steps:

	1.	Open the AppServiceProvider class located in the app/Providers directory.
	2.	Add the method call in the boot() function.

Code Example:

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use DrobnyDev\ApplicationVersioning\ApplicationVersioningServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            // Automatically retrieve and update the current Git tag
            ApplicationVersioningServiceProvider::getActualGitTag();
        }
    }


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Michal Drobny](https://github.com/54170028+misodrobny)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
