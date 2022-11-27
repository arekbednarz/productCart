<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductListApiTest extends TestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed CurrencySeeder');
    }

    public function test_api_get_products_list_format()
    {
        $response = $this->get(route('products.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "current_page",
                "data" => [],
                "first_page_url",
                "from",
                "last_page",
                "last_page_url",
                "links" => [],
                "next_page_url",
                "path",
                "per_page",
                "prev_page_url",
                "to",
                "total"
            ]);
    }

    public function test_api_get_products_list_pagination()
    {
        Product::factory()->count(5)->for(Currency::first())->create();

        $response = $this->get(route('products.index'));

        $this->assertEquals(5, $response->json('total'));
        $this->assertEquals(count($response->json('data')), 3);
    }

    public function test_api_get_products_list_pagination_second_page()
    {
        Product::factory()->count(5)->for(Currency::first())->create();

        $response = $this->get(route('products.index') . '?page=2');

        $this->assertEquals(5, $response->json('total'));
        $this->assertEquals(count($response->json('data')), 2);
    }
}
