<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductUpdateApiTest extends TestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed CurrencySeeder');
    }

    public function test_api_update_product()
    {
        $newProduct = Product::factory()->for(Currency::first())->create();
        $newCurrency = Currency::factory()->create();

        $response = $this->patchJson(route('products.update', $newProduct->id), [
            "title" => "New title",
            "price" => "999.99",
            "currency" => $newCurrency->symbol,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "title",
                    "price",
                ]
            ]);

        $dbUpdatedProduct = Product::first();

        $this->assertEquals("New title", $dbUpdatedProduct->title);
        $this->assertEquals("999.99", $dbUpdatedProduct->price);
        $this->assertEquals($newCurrency->symbol, $dbUpdatedProduct->currency->symbol);
    }
}
