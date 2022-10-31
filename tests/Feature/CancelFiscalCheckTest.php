<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelFiscalCheckTest extends TestCase
{
    use AuthenticateTrait, DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
         $data = [
             'env' => 'prod',
             'user' => [
                 'id' => 1,
                 'first' => 'Наиль', 'second' => 'Яппаров', 'middle' => null, 'email' => 'nail.95.kz@mail.ru',
                 'phone' => '+7(950)329-76-77',
             ],
             'order' => [
                 'id' => 200480, 'amount' => 6, 'has_loyalty' => 1
             ],
             'bonusesAmount' => 0.2,
             'loyaltySystemOperationId' => '100000281810',
             'lentaHost' => '123', 'lentaAgent' => '123',
         ];
        $response = $this->postJson('/api/pay/cancel-fiscal-check', $data, $this->getAuthHeader());
        $response->dump();
        $response->assertStatus(200);
    }
}
