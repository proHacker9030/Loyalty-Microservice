<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PromocodeTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_promo()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41895491, 'amount' => 6, 'has_loyalty' => 0
            ],
            'promocode' => 'test',
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/promocode/apply/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_cancel_promo()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41895491, 'amount' => 6, 'has_loyalty' => 1
            ],
            'promocode' => 'test',
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/promocode/cancel/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
