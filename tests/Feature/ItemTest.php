<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Requisition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    private function createRequisition(){
        $requisition = Requisition::create([
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ]);

        return $requisition;
    }

    public function test_get_paginate_items(){
        $this->json('GET', 'api/items')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'requisition_id', 'reference', 'name', 'requisition_info' => ['id', 'reference', 'name', 'description', 'status']]
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links' => [
                        '*' => ['url', 'label', 'active']
                    ],
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            );
    }

    public function test_get_item(){
        $requisition = $this->createRequisition();

        $item = Item::create([
            'requisition_id'    =>  $requisition->id,
            'reference'         =>  Str::uuid(),
            'name'              =>  ucfirst($this->faker->words(rand(2, 5), true)),
        ]);

        $this->json('GET', 'api/items/'.$item->id)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'item' => [
                            'id'                =>  $item->id,
                            'requisition_id'    =>  $item->requisition_id,
                            'reference'         =>  $item->reference,
                            'name'              =>  $item->name,
                            'requisition_info'  =>  [
                                'id'                =>  $requisition->id,
                                'reference'         =>  $requisition->reference,
                                'name'              =>  $requisition->name,
                                'description'       =>  $requisition->description,
                                'status'            =>  $requisition->status
                            ]
                        ]
                    ],
                ]
            );
    }

    public function test_create_item(){
        $requisition = $this->createRequisition();
        $item = [
            'requisition_id'    =>  $requisition->id,
            'reference'         =>  Str::uuid(),
            'name'              =>  ucfirst($this->faker->words(rand(2, 5), true)),
        ];

        $this->actingAs($this->user, 'sanctum');
        $this->json('POST', 'api/items', $item, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'item' => [
                            'id'                =>  2,
                            'requisition_id'    =>  $requisition->id,
                            'reference'         =>  $item['reference'],
                            'name'              =>  $item['name'],
                            'requisition_info'  =>  [
                                'id'                =>  $requisition->id,
                                'reference'         =>  $requisition->reference,
                                'name'              =>  $requisition->name,
                                'description'       =>  $requisition->description,
                                'status'            =>  $requisition->status
                            ]
                        ]
                    ],
                    'msg'   =>  'Item created successfully'
                ]
            );
    }

    public function test_update_item(){
        $requisition = $this->createRequisition();
        $item = Item::create([
            'requisition_id'    =>  $requisition->id,
            'reference'         =>  Str::uuid(),
            'name'              =>  ucfirst($this->faker->words(rand(2, 5), true)),
        ]);

        $itemUpdated = [
            'requisition_id'    =>  $requisition->id,
            'name'              =>  ucfirst($this->faker->words(rand(2, 5), true)),
        ];

        $this->actingAs($this->user, 'sanctum');
        $this->json('PATCH', 'api/items/'.$item->id, $itemUpdated, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'item' => [
                            'id'                =>  $item->id,
                            'requisition_id'    =>  $itemUpdated['requisition_id'],
                            'reference'         =>  $item->reference,
                            'name'              =>  $itemUpdated['name'],
                            'requisition_info'  =>  [
                                'id'                =>  $requisition->id,
                                'reference'         =>  $requisition->reference,
                                'name'              =>  $requisition->name,
                                'description'       =>  $requisition->description,
                                'status'            =>  $requisition->status
                            ]
                        ]
                    ],
                    'msg'   =>  'Item updated successfully'
                ]
            );
    }

    public function test_delete_item(){
        $requisition = $this->createRequisition();
        $itemData = [
            'requisition_id'    =>  $requisition->id,
            'reference'         =>  Str::uuid(),
            'name'              =>  ucfirst($this->faker->words(rand(2, 5), true)),
        ];
        $item = Item::create($itemData);

        $this->actingAs($this->user, 'sanctum');
        $this->json('DELETE', 'api/items/'.$item->id)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'msg'   =>  'Item deleted successfully'
                ]
            );
        $this->assertDatabaseMissing('items', $itemData);
    }
}
