<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('cannot order out of stock items', function () {
    Sanctum::actingAs($this->user);

    $product = Product::factory()->create(['stock_quantity' => 0]);

    $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 1]
        ]
    ])
    ->assertStatus(422)
    ->assertJsonValidationErrors(['items']);
});

it('decrements stock correctly after order', function () {
    Sanctum::actingAs($this->user);

    $product = Product::factory()->create(['stock_quantity' => 10]);

    $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 3]
        ]
    ])
    ->assertStatus(201);

    expect($product->fresh()->stock_quantity)->toBe(7);
});

it('creates order with correct total amount', function () {
    Sanctum::actingAs($this->user);

    $product = Product::factory()->create([
        'stock_quantity' => 10,
        'price' => 25.00,
    ]);

    $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2]
        ]
    ])
    ->assertStatus(201)
    ->assertJsonPath('data.total_amount', 50);
});