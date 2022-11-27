<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usdCurrencyId = Currency::query()->where('symbol', 'USD')->first()->id;

        $products = [
            [
                'title' => 'Fallout',
                'price' => 1.99,
                'currency_id' => $usdCurrencyId,
            ],
            [
                'title' => 'Don’t Starve',
                'price' => 2.99,
                'currency_id' => $usdCurrencyId,
            ],
            [
                'title' => 'Baldur’s Gate',
                'price' => 3.99,
                'currency_id' => $usdCurrencyId,
            ],
            [
                'title' => 'Icewind Dale',
                'price' => 4.99,
                'currency_id' => $usdCurrencyId,
            ],
            [
                'title' => 'Bloodborne',
                'price' => 5.99,
                'currency_id' => $usdCurrencyId,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
