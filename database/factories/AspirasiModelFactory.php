<?php

namespace Database\Factories;

use App\Models\AspirasiModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AspirasiModelFactory extends Factory
{
    protected $model = AspirasiModel::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'status' => 'pending',
            'attachments' => [],
        ];
    }
}
