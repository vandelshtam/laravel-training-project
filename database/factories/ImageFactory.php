<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Info;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => 'img/demo/avatars/type2.png',
            'user_id' => User::factory()->create(),
            'info_id' => Info::factory()->create(),
            'imageable_id' => Post::factory()->create(),
            'imageable_type' => 'App\Models\Post',
            'chat_id' => Chat::factory()->create(),
            'post_id' => Post::factory()->create(),
        ];
    }
}
