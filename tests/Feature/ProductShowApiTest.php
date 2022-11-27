<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductShowApiTest extends TestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed CurrencySeeder');
    }

    public function test_api_get_products_show()
    {
        $newProduct = Product::factory()->for(Currency::first())->create();

        $response = $this->get(route('products.show', $newProduct->id));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "title",
                    "price",
                ]
            ]);
    }
}
