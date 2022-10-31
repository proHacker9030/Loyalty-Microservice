<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfirmOrderTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_confrim()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77',
            ],
            'order' => [
                'id' => 200478, 'amount' => 10, 'has_loyalty' => 1,
            ],
            'lentaHost' => '123', 'lentaAgent' => '123',
            'bonusesAmount' => 0.2
        ];
        $response = $this->postJson('/api/pay/confirm-order', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
