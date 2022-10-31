<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefundTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_refund_order()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41895491, 'amount' => 6, 'has_loyalty' => 0,
                'carts' => [
                    ['id' => 1, 'price' => 500]
                ]
            ],
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/refund/order/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_refund_cart()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41895491, 'amount' => 6, 'has_loyalty' => 0,
                'carts' => [
                    ['id' => 1, 'price' => 500]
                ]
            ],
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/refund/cart/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
