<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetAvailableBonusesTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_bonuses()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'lentaHost' => '123', 'lentaAgent' => '123', 'has_loyalty' => 0
        ];
        $response = $this->getJson('/api/bonuses/available/?' . http_build_query($data), $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_get_bonuses_by_order()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'cardNumber' => '123'
            ],
            'order' => [
                'id' => 41941182, 'amount' => 4, 'has_loyalty' => 0
            ],
            'lentaHost' => '123', 'lentaAgent' => '123'
        ];
        $response = $this->getJson('/api/bonuses/available/?' . http_build_query($data), $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_get_bonuses_by_project()
    {
        $data = [
            'env' => 'prod',
            'user' => [
                'id' => 1,
                'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                'phone' => '+7(950)329-76-77', 'card_number' => '123'
            ],
            'projectToken' => 'BZie5VwHxy',
             'order' => ['id' => 123, 'amount' => 2, 'has_loyalty' => 1],
            'lentaHost' => '123', 'lentaAgent' => '123'
        ];
        $response = $this->getJson('/api/bonuses/available/?' . http_build_query($data), $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
