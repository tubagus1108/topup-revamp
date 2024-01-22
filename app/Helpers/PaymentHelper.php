<?php

namespace App\Helpers;

class PaymentHelper
{
    public static function DFStatus($x)
    {
        if ($x == 'Transaksi Pending') return 'Pending';
        if ($x == 'Transaksi Gagal') return 'Fail';
        if ($x == 'Transaksi Sukses') return 'Success';
        return 'Pending';
    }
}
