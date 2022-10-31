<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpendBonusesTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_spend()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41941182, 'amount' => 6, 'has_loyalty' => 0
            ],
            'bonusesAmount' => 10,
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/bonuses/spend/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_re_spend()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'loyaltyUid' => '1'
            ],
            'order' => [
                'id' => 200477, 'amount' => 20.2, 'has_loyalty' => 1,
                'carts' => [['id' => 1], ['id' => 2, 'price' => 0.5]]
            ],
            'bonusesAmount' => 0.2,
            'lentaHost' => 'http://127.0.0.1:1120/tickets/wsdl', 'lentaAgent' => '372F05D3-QWWE-34WEW-43434'
        ];
        $response = $this->postJson('/api/bonuses/re-spend/', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
