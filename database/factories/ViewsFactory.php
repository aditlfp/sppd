<?php

namespace Database\Factories;

use App\Models\Views;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Views>
 */
class ViewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Views::class;
    public function definition(): array
    {
        return [
            'viewable' => "0"
        ];
    }
}
