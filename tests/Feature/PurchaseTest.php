<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PurchaseTest extends TestCase
{
    public function test_purchase_index_renders_successfully()
    {
        $response = $this->get('/purchases');
        $response->assertStatus(200);
    }

    public function test_purchase_create_renders_successfully()
    {
        $response = $this->get('/purchases/create');
        $response->assertStatus(200);
    }
}
