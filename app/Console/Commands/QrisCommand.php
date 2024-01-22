<?php

namespace App\Console\Commands;

use App\Helpers\Qris;
use App\Models\Deposits;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class QrisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:qris-run';

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
        //
        $email = config('services.qris.email');
        $password = config('services.qris.password');

        try {
            $data = Deposits::where('status', 'Pending')->get();
            if ($data) {
                foreach ($data as $item) {
                    if ($item->type == 'qris') {
                        $app = new Qris(
                            $email,
                            $password,
                            $item['total_amount'],
                            null,
                            null,
                            null,
                        );

                        $mutasi = $app->mutasi();

                        if ($mutasi) {
                            $deposit = Deposits::where('total_amount', $mutasi[0]['nominal'])->where('status', 'Pending')->first();
                            $user = User::find($deposit['user_id']);

                            $user->balance += $item['amount'];

                            $user->save();
                            $deposit->status = "Success";
                            $deposit->save();

                            Log::info([
                                'message' => "Succes get mutasi qris"
                            ]);
                        } else {
                            Log::info([
                                'message' => "mutasi tidak di temukan"
                            ]);
                        }
                    } else {
                        Log::info([
                            'message' => "mutasi qris tidak di temukan"
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::info($e);
        }
    }
}
