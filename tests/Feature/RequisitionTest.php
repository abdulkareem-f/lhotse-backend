<?php

namespace Tests\Feature;

use App\Jobs\SendRequisitionSubmitted;
use App\Models\Requisition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class RequisitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_paginate_requisitions(){
        $this->json('GET', 'api/requisitions')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'reference', 'name', 'description', 'status']
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

    public function test_get_requisition(){
        $requisition = Requisition::create([
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ]);

        $this->json('GET', 'api/requisitions/'.$requisition->id)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'requisition' => [
                            'id'            =>  $requisition->id,
                            'reference'     =>  $requisition->reference,
                            'name'          =>  $requisition->name,
                            'description'   =>  $requisition->description,
                            'status'        =>  $requisition->status
                        ]
                    ],
                ]
            );
    }

    public function test_create_requisition(){
        $requisition = [
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ];

        $this->actingAs($this->user, 'sanctum');
        $this->json('POST', 'api/requisitions', $requisition, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'requisition' => [
                            'id'            =>  6,
                            'reference'     =>  $requisition['reference'],
                            'name'          =>  $requisition['name'],
                            'description'   =>  $requisition['description'],
                            'status'        =>  $requisition['status']
                        ]
                    ],
                    'msg'   =>  'Requisition created successfully'
                ]
            );
    }

    public function test_update_requisition(){
        $requisition = Requisition::create([
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ]);

        $requisitionUpdated = [
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ];

        $this->actingAs($this->user, 'sanctum');
        $this->json('PATCH', 'api/requisitions/'.$requisition->id, $requisitionUpdated, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'requisition' => [
                            'id'            =>  $requisition->id,
                            'reference'     =>  $requisition->reference,
                            'name'          =>  $requisitionUpdated['name'],
                            'description'   =>  $requisitionUpdated['description'],
                            'status'        =>  $requisitionUpdated['status'],
                        ]
                    ],
                    'msg'   =>  'Requisition updated successfully'
                ]
            );
    }

    public function test_delete_requisition(){
        $requisitionData = [
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ];
        $requisition = Requisition::create($requisitionData);

        $this->actingAs($this->user, 'sanctum');
        $this->json('DELETE', 'api/requisitions/'.$requisition->id)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'msg'   =>  'Requisition deleted successfully'
                ]
            );
        $this->assertDatabaseMissing('requisitions', $requisitionData);
    }

    public function test_submit_requisition(){
        Queue::fake();

        $requisition = Requisition::create([
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300)),
            'status'        =>  'draft'
        ]);

        $this->actingAs($this->user, 'sanctum');
        $this->json('POST', "api/requisitions/{$requisition->id}/submit")
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'requisition' => [
                            'id'            =>  $requisition->id,
                            'reference'     =>  $requisition->reference,
                            'name'          =>  $requisition->name,
                            'description'   =>  $requisition->description,
                            'status'        =>  'submitted',
                        ]
                    ],
                    'msg'   =>  'Requisition has been submitted successfully'
                ]
            );

        Queue::assertPushed(SendRequisitionSubmitted::class);
    }
}
