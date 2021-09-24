<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Info;
use App\Models\User;
use App\Models\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_chat' => $this->faker->text(14),
            'name' => $this->faker->text(14),
            'role' => 'author',
            'favorites' => 0,
            'banned' => 0,
            'user_id' => User::factory()->create(),
            'chat_avatar' => 'img/demo/avatars/type2.png',
            'info_id' => Info::factory()->create(),
            'author_user_id' => Social::factory()->create(),
        ];
    }
}
