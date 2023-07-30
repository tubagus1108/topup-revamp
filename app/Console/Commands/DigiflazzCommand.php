<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Services;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DigiflazzCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digiflazz:run';

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
        // Mengambil kredensial API Digiflazz dari konfigurasi Laravel
        $username = config('services.digiflazz.username');
        $secretKey = config('services.digiflazz.secret_key');
        $baseUrl = config('services.digiflazz.base_url');
        
        // Membuat tanda tangan (signature) untuk permintaan API
        $signature = md5($username . $secretKey . "pricelist");

        // Menyiapkan data permintaan API
        $data = [
            'username' => $username,
            'sing' => $signature
        ];

        // Menyiapkan header permintaan API
        $header = [
            'Content-Type: application/json',
        ];

        // Melakukan permintaan API menggunakan metode connect() (asumsikan sudah didefinisikan sebelumnya)
        $response = $this->connect($baseUrl, $data, $header);

        // Inisialisasi array untuk menyimpan kategori yang berhasil dibuat
        $createdCategories = [];
        $createdServices = [];

        // Memeriksa apakah respon dari API mengandung data dan apakah datanya merupakan array
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $item) {
                // Menyiapkan data kategori berdasarkan informasi dari API
                $categoryData = [
                    'name' => Str::lower($item['brand']),
                    'code' => str_replace(' ', '-', Str::lower($item['brand'])),
                    'brand' => Str::upper($item['brand']),
                    'status' => $item['seller_product_status'] ? 'active' : 'inactive',
                    'type' => Str::lower($item['category']),
                ];
                
                // Memeriksa apakah kategori dengan nama dan tipe yang sama sudah ada di database
                $existingCategory = Category::where('name', $categoryData['name'])
                                            ->where('type', $categoryData['type'])
                                            ->first();                
                
                // Menyiapkan data Service berdasarkan kategori di atas
                $servicesData = [
                    'name' => $item['product_name'],
                    'sid' => $item['buyer_sku_code'],
                    'price' => $item['price'],
                    'price_member' => $item['price'] + 1000,
                    'price_platinum' => $item['price'] + 750,
                    'price_gold' => $item['price'] + 500,
                    'profit' => 0,
                    'profit_member' => ($item['price'] + 1000) - $item['price'],
                    'profit_platinum' => ($item['price'] + 750) - $item['price'],
                    'profit_gold' => ($item['price'] + 500) - $item['price'],
                    'notes' => $item['desc'],
                    'status' => ($item['seller_product_status'] === true ? "available" : "unavailable"),
                    'provider' => 'digiflazz',
                ];
                
                $existingService = Services::where('name', $item['product_name'])->first();
                if ($existingCategory) {
                    // Jika kategori sudah ada, tambahkan service ke dalam array $createdServices
                    $servicesData['category_id'] = $existingCategory['id'];
                    if($existingService){
                        $serviceUpdate = Services::editService($servicesData,$existingService->id);
                        $createdServices[] = $serviceUpdate;
                    }else{
                        $services = Services::createService($servicesData);
                        $createdServices[] = $services;
                    }
                } else {
                    // Jika kategori belum ada, buat kategori baru menggunakan metode createCategory()
                    $category = Category::createCategory($categoryData);
                    $servicesData['category_id'] = $category['id'];
                    if($existingService){
                        $serviceUpdate = Services::editService($servicesData,$existingService->id);
                        $createdServices[] = $serviceUpdate;
                    }else{
                        $services = Services::createService($servicesData);
                        $createdServices[] = $services;
                    }
                    // Tambahkan kategori yang berhasil dibuat ke dalam array $createdCategories
                    $createdCategories[] = $category;
                    // $createdServices[] = $services;
                }
            }
        }

        // Mengembalikan array yang berisi data kategori yang berhasil dibuat
        return ['categories' => $createdCategories, 'services' => $createdServices];

    }


    private function connect($baseUrl,$data,$header){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl."price-list");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $chresult = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($chresult, true);
        return $json_result;
    }
}
