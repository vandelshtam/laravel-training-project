<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Info;
use App\Models\User;
use App\Models\Social;
use App\Models\Userlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserlistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Userlist::class;

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
            'status-chat' => 0,
            'role' => 'participant',
            'favorites' => 0,
            'banned' => 0,
            'user_id' => User::factory()->create(),
            'chat_avatar' => 'img/demo/avatars/type2.png',
            'info_id' => Info::factory()->create(),
            'userlistable_id' => Chat::factory()->create(),
            'userlistable_type' => 'App\Models\Chat',
            'chat_id' => Chat::factory()->create(),
        ];
    }
}
