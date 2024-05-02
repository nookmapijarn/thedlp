<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    public $semestry = '';
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Set ตัวแปร
        $this->semestry = '67/1';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Get ตัวแปร
        //$customVariable = $this->app->make('custom_variable');
        //dd($customVariable);  // แสดงค่าตัวแปร
    }
    public function setSemestry($new_semestry)
    {
        $this->semestry = $new_semestry;
    }
    public function getSemestry()
    {
        return $this->semestry;
    }
}
