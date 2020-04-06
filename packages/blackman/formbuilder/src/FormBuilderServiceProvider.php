<?php
namespace Formbuilder;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->loadRoutesFrom(__DIR__ ."/routes/web.php");

        $this->loadViewsFrom(__DIR__ ."/./../resources/views", 'formbuilder');
    }

    public function register()
    {
        $this->registerpublishables();
    }

    private function registerpublishables() {
//        ini_set('memory_limit', '-1');
        $basePath = dirname(__DIR__);

        $arrPublishables = [
          'migrations'  =>  [
              "$basePath/publishables/database/migrations"  =>  database_path('migrations'),
          ],
            'config'    =>  [
                "$basePath/publishables/config/form-builder.php"    =>  config_path('form-builder.php'),
            ]
        ];

        foreach ($arrPublishables as $group =>  $paths) {
            $this->publishes($paths,$group);
        }
    }


}