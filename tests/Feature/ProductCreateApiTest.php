<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductCreateApiTest extends TestCase
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed CurrencySeeder');
    }

    public function test_api_add_new_product()
    {
        $newProduct = Product::factory()->for(Currency::first())->make();

        $response = $this->postJson(route('products.store'), [
            "title" => $newProduct->title,
            "price" => $newProduct->price,
            "currency" => $newProduct->currency->symbol,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "title",
                    "price",
                ]
            ]);

        $dbProduct = Product::first();
        $currency = Currency::first();

        $this->assertEquals($dbProduct->title, $newProduct->title);
        $this->assertEquals($dbProduct->price, $newProduct->price);
        $this->assertEquals($dbProduct->currency_id, $currency->id);
    }

    public function test_api_add_new_product_without_title_validation()
    {
        $newProduct = Product::factory()->make();

        $response = $this->postJson(route('products.store'), [
            "price" => $newProduct->price,
            "currency" => $newProduct->currency,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                "success",
                "message",
                "data" => [
                    "title",
                ]
            ]);

        $dbProduct = Product::first();

        $this->assertNull($dbProduct);
    }

    public function test_api_add_new_product_unique_title_validation()
    {
        $newProduct = Product::factory()->for(Currency::first())->create();

        $response = $this->postJson(route('products.store'), [
            "title" => $newProduct->title,
            "price" => $newProduct->price,
            "currency" => $newProduct->currency,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                "success",
                "message",
                "data" => [
                    "title",
                ]
            ]);
    }

    public function test_api_add_new_product_without_price_validation()
    {
        $newProduct = Product::factory()->make();

        $response = $this->postJson(route('products.store'), [
            "title" => $newProduct->title,
            "currency" => $newProduct->currency,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                "success",
                "message",
                "data" => [
                    "price",
                ]
            ]);

        $dbProduct = Product::first();

        $this->assertNull($dbProduct);
    }

    public function test_api_add_new_product_not_existing_currency_validation()
    {
        $newProduct = Product::factory()->make();

        $response = $this->postJson(route('products.store'), [
            "title" => $newProduct->title,
            "price" => $newProduct->price,
            "currency" => "INVALID",
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                "success",
                "message",
                "data" => [
                    "currency",
                ]
            ]);

        $dbProduct = Product::first();

        $this->assertNull($dbProduct);
    }

}
