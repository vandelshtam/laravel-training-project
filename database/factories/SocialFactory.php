<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Social::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'telegram' => $this->faker->text(15),
            'instagram' => $this->faker->text(15),
            'vk' => $this->faker->text(15),
            'user_id' => 5,
        ];
    }
}
