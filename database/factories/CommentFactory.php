<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->text(56),
            'user_id' => User::factory()->create(),
            'chat_avatar' => 'img/demo/avatars/type2.png',
            'commentable_id' => Post::factory()->create(),
            'commentable_type' => 'App\Models\Post',
            'post_id' => Post::factory()->create(),
            'banned' => 0,
        ];
    }
}
