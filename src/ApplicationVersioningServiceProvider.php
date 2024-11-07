<?php

namespace DrobnyDev\ApplicationVersioning;

use DrobnyDev\ApplicationVersioning\Commands\IncreaseMajorVersionCommand;
use DrobnyDev\ApplicationVersioning\Commands\IncreaseMinorVersionCommand;
use DrobnyDev\ApplicationVersioning\Commands\IncreasePatchVersionCommand;
use DrobnyDev\ApplicationVersioning\Commands\IncreaseVersionCommand;
use Exception;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\File;

class ApplicationVersioningServiceProvider extends PackageServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton(ApplicationVersioning::class, fn () => new ApplicationVersioning);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('application-versioning')
            ->hasConfigFile('application-versioning')
            ->hasCommand(IncreaseMajorVersionCommand::class)
            ->hasCommand(IncreaseMinorVersionCommand::class)
            ->hasCommand(IncreasePatchVersionCommand::class)
            ->hasCommand(IncreaseVersionCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Hello, and welcome to Application Versioning package for Laravel!');

                        if (! file_exists(base_path('version.yaml'))) {
                            try {
                                file_put_contents(base_path('version.yaml'), "version:\n\t".
                                    'current: { major: 0, minor: 0, patch: 1, format: $major.$minor.$patch - $git_hash }');

                                $command->info('version.yaml file created successfully.');
                            } catch (Exception) {
                                $command->error('Could not create version.yaml file. Please create it manually.');
                            }
                        } else {
                            $command->info('version.yaml file already exists.');
                        }
                    })
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('misodrobny/application-versioning');
            });
    }


    public static function getActualGitTag(): void
    {
        $latestTag = trim(shell_exec('cd ' . base_path() . ' && git describe --tags --abbrev=0'));

        if ($latestTag) {
            [$major, $minor, $patch] = explode('.', $latestTag);

            $versionContent = "version:\n" .
                "  current: { major: $major, minor: $minor, patch: $patch, format: \"\$major.\$minor.\$patch - \$git_hash\" }";
        } else {
            $versionContent = "version:\n" .
                "  current: { major: 0, minor: 0, patch: 1, format: \"0.0.1 - \$git_hash\" }";
        }

        File::put(base_path('version.yaml'), $versionContent);
    }
}
