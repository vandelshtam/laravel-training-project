<?php

namespace Database\Factories;

use App\Models\Info;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Integer;
use Ramsey\Uuid\Type\Integer as TypeInteger;

class InfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Info::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'occupation' => $this->faker->text(25),
            'location' => $this->faker->text(25),
            'position' => $this->faker->text(25),
            'phone' => '+05 333 66 88',
            'status' => 0,
            'user_id' => User::factory()->create(),
            'avatar' => 'img/demo/avatars/avatar-m.png',   
        ];
    }
}
