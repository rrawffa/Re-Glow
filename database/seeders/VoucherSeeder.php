<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run()
    {
       $this->call(VoucherSeeder::class);

        $now = Carbon::now();
        $data = [
            ['name'=>'10% Off Eco Bag','brand'=>'GreenMart','required_points'=>150,'expiration_date'=>$now->addDays(30),'stock'=>12,'image_url'=>'/assets/vouchers/eco-bag.jpg','description'=>'Diskon 10% untuk pembelian Eco Bag dari GreenMart.'],
            ['name'=>'Free Coffee','brand'=>'BeanCycle','required_points'=>200,'expiration_date'=>$now->copy()->addDays(45),'stock'=>20,'image_url'=>'/assets/vouchers/free-coffee.jpg','description'=>'Gratis 1 kopi medium di BeanCycle.'],
            ['name'=>'5k Discount','brand'=>'LocalStore','required_points'=>100,'expiration_date'=>$now->copy()->addDays(15),'stock'=>5,'image_url'=>'/assets/vouchers/5k-off.jpg','description'=>'Potongan Rp 5.000 untuk pembelian Rp 50.000 ke atas.'],
            // ... tambah sampai minimal 12 entri agar fitur Load More terlihat
        ];

        foreach ($data as $item) {
            Voucher::create([
                'name' => $item['name'],
                'brand' => $item['brand'],
                'description' => $item['description'],
                'required_points' => $item['required_points'],
                'expiration_date' => $item['expiration_date'],
                'stock' => $item['stock'],
                'image_url' => $item['image_url'],
            ]);
        }
    }
}
