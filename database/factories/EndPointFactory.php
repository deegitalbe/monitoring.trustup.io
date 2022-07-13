<?php

namespace Database\Factories;

use App\Models\EndPoint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EndPointFactory extends Factory
{
    protected $model = EndPoint::class;

    public function definition(): array
    {
        return [
            'is_monitored' => true,
            'name' => $this->faker->name(),
            'url' => $this->faker->url(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
