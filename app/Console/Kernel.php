<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DatabaseBackup',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('\App\Http\Controllers\AdminAssetsController@getItemMasterDataApi')->hourly()->between('9:00', '23:00');
        $schedule->call('\App\Http\Controllers\AdminAssetsController@getItemMasterUpdatedDataApi')->hourly()->between('9:00', '23:00');
        // $schedule->call('\App\Http\Controllers\BrandController@getBrandUpdatedAPI')->hourly()->between('9:00', '21:00');
        // $schedule->call('\App\Http\Controllers\BrandController@getBrandCreatedAPI')->hourly()->between('9:00', '21:00');
        // $schedule->call('\App\Http\Controllers\CategoryController@getCategoryUpdatedAPI')->hourly()->between('9:00', '21:00');
        // $schedule->call('\App\Http\Controllers\CategoryController@getCategoryCreatedAPI')->hourly()->between('9:00', '21:00');
        
        $schedule->call('\App\Http\Controllers\AdminOrderSchedulesController@deactivateSchedule')->hourly(); //->dailyAt('04:00');
        
        $schedule->command('mysql:backup')->daily()->at('23:50');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
