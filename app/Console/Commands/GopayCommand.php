<?php

namespace App\Console\Commands;

use App\Helpers\GojekPay;
use App\Models\Deposits;
use App\Models\Gopay;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class GopayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gopay-run';

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
        $nomor = config('services.gopay.nomor');

        $getTranction = Gopay::where('phone', $nomor)->latest()->first();
        if ($getTranction) {
            $app = new GojekPay($getTranction->token);
            $getData = json_decode($app->getTransactionHistory(), true);
            $list_transaksi = $getData['data']['success'];

            $transaksi = [];
            foreach ($list_transaksi as $transfer) {
                if ($transfer['type'] == "credit") {
                    $transaksi = $transfer;

                    // Mengeupdate record yang memenuhi kondisi di tabel Deposits
                    $deposit = Deposits::where('amount', $transaksi['amount']['value'])->where('status', 'Pending')->first();

                    // Memeriksa apakah $deposit tidak null
                    if ($deposit) {
                        // Mencari model User berdasarkan user_id
                        $user = User::find($deposit['user_id']);

                        // Menambahkan nilai ke balance User
                        $user->balance += $deposit['amount'];

                        // Menyimpan perubahan ke model User
                        $user->save();

                        // Mengupdate status Deposit menjadi Success
                        $deposit->status = 'Success';
                        $deposit->save();
                    }
                }
            }

            Log::info("=== SUKSES ====", [
                'message' => "Berhasil get Mutasi Cron",
                'data' => $transaksi
            ]);
        } else {
            Log::info("PAYMENT GOPAY BELUM DI SET");
        }
    }
}
