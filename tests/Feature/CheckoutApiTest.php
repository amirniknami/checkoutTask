<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *
 */
class CheckoutApiTest extends TestCase
{
    /**
     * @scenario if we forgot to pass the array of items system should give us an error
     * @when we forgot to give an APi an array of items
     * @then it should return should 422 unprocessable entity response
     */
    public function test_items_field_is_required()
    {
        $response = $this->postJson(route('checkout'), [
            'rules' => $this->getSampleRules()
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'items' => []
            ]
        ])->assertStatus(422);
    }

    /**
     * @scenario if the type of items is not array API should fail
     * @when we give  an APi an empty array of items
     * @then it should return should 422 unprocessable entity response
     */
    public function test_items_field_should_be_array()
    {
        $response = $this->postJson(route('checkout'), [
            'items' => 'string',
            'rules' => $this->getSampleRules()
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'items' => []
            ]
        ])->assertStatus(422);
    }

    /**
     * @scenario If we forgot to pass an array of rules the API should fail.
     * @when we forgot to give an APi an array of rules
     * @then it should return should 422 unprocessable entity response
     */
    public function test_rules_field_is_required()
    {
        $response = $this->postJson(route('checkout'), [
            'items' => $this->getSampleItems()
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'rules' => []
            ]
        ])->assertStatus(422);
    }

    /**
     * @scenario if we give not array to API it should fail
     * @when we give  an APi an empty array of items
     * @then it should return should 422 unprocessable entity response
     */
    public function test_rules_field_is_array()
    {
        $response = $this->postJson(route('checkout'), [
            'items' => $this->getSampleItems(),
            'rules' => 'string'
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'rules' => []
            ]
        ])->assertStatus(422);
    }

    /**
     * @scenario if we provide correct items and rules it should return correct price
     * @when we give  an APi an array ot items and array of rules
     * @then it should return total price correct with 200 status
     */
    public function test_return_total_price_is_correct()
    {
        // A,B => 80
        $response = $this->postJson(route('checkout'), [
            'items' => [
                [
                    'name' => 'A',
                    'price' => 50
                ],
                [
                    'name' => 'B',
                    'price' => 30
                ]
            ]
        ]);

        $response->assertJson([
            'total' => 80
        ])->assertStatus(200);

        // A,A => 100
        $response = $this->postJson(route('checkout'), [
            'items' => [
                [
                    'name' => 'A',
                    'price' => 50
                ],
                [
                    'name' => 'A',
                    'price' => 50
                ],
            ]
        ]);

        $response->assertJson([
            'total' => 100
        ])->assertStatus(200);

        // A,A,A => 130
        $response = $this->postJson(route('checkout'), [
            'items' => [
                [
                    'name' => 'A',
                    'price' => 50
                ],
                [
                    'name' => 'A',
                    'price' => 50
                ],
                [
                    'name' => 'A',
                    'price' => 50
                ],
            ]
        ]);

        $response->assertJson([
            'total' => 130
        ])->assertStatus(200);

        // C.D,B,A => 115
        $response = $this->postJson(route('checkout'),[
            'items' => [
                [
                    'name'  => 'C',
                    'price' => 20
                ],
                [
                    'name'  => 'D',
                    'price' => 20
                ],
                [
                    'name'  => 'B',
                    'price' => 30
                ],
                [
                    'name'  => 'A',
                    'price' => 50
                ]
            ]
        ]);

        $response->assertJson([
            'total' => 115
        ])->assertStatus(200);

    }

    /**
     * @return array[]
     */
    public function getSampleRules()
    {
        return [
            [
                'product' => 'A',
                'quantities' => 3,
                'special_price' => 130
            ],
            [
                'product' => 'B',
                'quantities' => 2,
                'special_price' => 45
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function getSampleItems()
    {
        return [
            [
                'name' => 'A',
                'price' => 50
            ],
            [
                'name' => 'B',
                'price' => 30
            ],
            [
                'name' => 'C',
                'price' => 20
            ],
            [
                'name' => 'D',
                'price' => 15
            ],
        ];
    }
}
