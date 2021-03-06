<?php

namespace Database\Factories;

use App\Models\Info;
use App\Models\Post;
use App\Models\User;
use App\Models\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_post' => $this->faker->text(14),
            'title_post' => $this->faker->text(28),
            'text' => $this->faker->text(56),
            'favorites' => 0,
            'banned' => 0,
            'user_id' => User::factory()->create(),
            'avatar_post' => 'img/demo/avatars/type2.png',
            'info_id' => Info::factory()->create(),
            'social_id' => Social::factory()->create(),
            'postable_id' => User::factory()->create(),
        ];
    }
}
