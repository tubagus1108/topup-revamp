<?php

namespace App\Console\Commands;

use App\Models\Deposits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConfirmDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:run';

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
        $data = Deposits::where('status','Pending')->get();
        Log::info(json_decode($data));
        
    }
}
