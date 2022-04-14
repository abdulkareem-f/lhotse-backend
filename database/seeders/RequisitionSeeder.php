<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Requisition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requisition::factory()
            ->count(25)
            ->has(Item::factory()->count(rand(3, 15)))
            ->create();
    }
}
