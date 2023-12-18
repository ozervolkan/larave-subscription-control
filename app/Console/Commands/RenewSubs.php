<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\RenewSubsJobs;

class RenewSubs extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:RenewSubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Abonelikleri yenileyecek job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RenewSubsJobs::dispatch();
        $this->info('Abonelikler yenilenmek için hazır. php artisan queue:work komutunu çalıştırabilirsiniz.');
    }
}
