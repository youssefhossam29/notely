<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate fake image using Faker
        $image = fake('en')->image(
            dir: public_path('uploads/notes/'),
            width:400,
            height: 300,
            category: "avatar",
            fullPath: false
        );

        return [
            //
            'title' => fake()->unique()->word,
            'content' => fake()->paragraph,
            'image' =>  fake()->randomElement( ['note.png', null] ) ,
            'slug' =>  Str::random(10) . time(),
            'is_pinned' => fake()->randomElement( [ false, true ] ),
            'created_at' => now(),
            'deleted_at' =>  fake()->randomElement( [now(), null] )
        ];
    }
}
