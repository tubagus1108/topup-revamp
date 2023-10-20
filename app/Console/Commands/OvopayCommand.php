<?php

namespace App\Console\Commands;

use App\Helpers\Ovo;
use App\Models\Deposits;
use App\Models\OvoPay;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OvopayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ovopay-run';

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
        $nomor = config('services.ovopay.nomor');

        $getTranction = OvoPay::where('phone', $nomor)->latest()->first();
        if ($getTranction) {
            $app = new Ovo($getTranction->token);
            $getData = json_decode($app->transactionHistory(), true);
            $list_transaksi = $getData['data'];
            if ($list_transaksi['status'] == 200) {
                // dd($list_transaksi);
                try {
                    foreach ($list_transaksi['orders'][0]['complete'] as $transaction => $key) {
                        $data = json_decode(json_encode($key), true);
                        $incomingTransfer = $data['transaction_type'];
                        if ($incomingTransfer == "TOPUP CASH" || $incomingTransfer == "TOPUP TRANSFER FEE" || $incomingTransfer == "FINANCIAL") { //cek apakah ada status incoming transfer jika ada push ke array
                            // dd($data);
                            Log::info("=== SUKSES ====", [
                                'message' => "Berhasil get Mutasi Cron OVO",
                                'data' => $data
                            ]);
                            // Mengeupdate record yang memenuhi kondisi di tabel Deposits
                            $deposit = Deposits::where('amount', $data['transaction_amount'])->where('status', 'Pending')->first();

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
                } catch (Exception $e) {
                    Log::info("message", [
                        "message" => $e
                    ]);
                    // Log or handle the exception, if needed
                }
            }
        } else {
            Log::info("PAYMENT OVO BELUM DI SET");
        }
    }
}
