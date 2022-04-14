<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Requisition>
 */
class RequisitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'reference'     =>  Str::uuid(),
            'name'          =>  ucfirst($this->faker->words(rand(2, 5), true)),
            'description'   =>  $this->faker->text(rand(150, 300))
        ];
    }
}
