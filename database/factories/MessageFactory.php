<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Info;
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message' => $this->faker->text(56),
            'name' => $this->faker->text(14),
            'user_id' => User::factory()->create(),
            'chat_avatar' => 'img/demo/avatars/type2.png',
            'info_id' => Info::factory()->create(),
            'messageable_id' => Chat::factory()->create(),
            'messageable_type' => 'App\Models\Chat',
            'chat_id' => Chat::factory()->create(),
        ];
    }
}
