<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can list products', function () {
    Product::factory()->count(5)->create();

    $this->actingAs($this->user)
        ->getJson('/api/products')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name', 'sku', 'price', 'stock_quantity']]]);
});

it('can filter products by category', function () {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);
    Product::factory()->count(2)->create();

    $this->actingAs($this->user)
        ->getJson("/api/products?category_id={$category->id}")
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can search products by name or sku', function () {
    Product::factory()->create(['name' => 'Special Widget', 'sku' => 'SW-001']);
    Product::factory()->create(['name' => 'Regular Item', 'sku' => 'RI-002']);

    $this->actingAs($this->user)
        ->getJson('/api/products?search=Special')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});