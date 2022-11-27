<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductDeleteApiTest extends TestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed CurrencySeeder');
    }

    public function test_api_delete_product()
    {
        $dbProduct = Product::factory()->for(Currency::first())->create();
        $this->assertEquals(1, Product::count());

        $response = $this->delete(route('products.destroy', $dbProduct->id));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "message",
            ]);

        $this->assertEquals(0, Product::count());
    }

    public function test_api_delete_not_existing_product()
    {
        $response = $this->delete(route('products.destroy', 123));

        $response
            ->assertStatus(404)
            ->assertJsonStructure([
                "message",
            ]);
    }
}
