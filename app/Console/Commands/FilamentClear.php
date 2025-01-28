<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FilamentClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('filament:optimize-clear');
        $this->info('Filament cache cleared');
        Artisan::call('filament:optimize');
        $this->info('Filament cache optimized');
        Artisan::call('icons:cache');
        $this->info('Icons cache cleared');
        Artisan::call('cache:clear');
        $this->info('Laravel cache cleared');
        Artisan::call('config:clear');
        $this->info('Laravel config cache cleared');
        Artisan::call('view:clear');
        $this->info('Laravel view cache cleared');
        Artisan::call('route:clear');
        $this->info('Laravel route cache cleared');
        Artisan::call('optimize:clear');
        $this->info('Laravel optimize cache cleared');
    }
}
