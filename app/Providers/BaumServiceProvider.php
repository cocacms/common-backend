<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Providers;

use App\Services\Baum\InstallCommand;
use Baum\Generators\MigrationGenerator;
use Baum\Generators\ModelGenerator;
use Baum\Providers\BaumServiceProvider as BaseServiceProvider;

class BaumServiceProvider extends  BaseServiceProvider
{
    /**
     * Register the 'baum:install' command.
     *
     * @return void
     */
    protected function registerInstallCommand() {
        $this->app->singleton('command.baum.install', function($app) {
            $migrator = new MigrationGenerator($app['files']);
            $modeler  = new ModelGenerator($app['files']);

            return new InstallCommand($migrator, $modeler);
        });
    }
}