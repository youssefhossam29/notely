<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;
use App\Models\User;
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
        return [
            //
            'title' => fake()->unique()->word,
            'content' => fake()->paragraph,
            'slug' =>  Str::random(10) . time(),
            'is_pinned' => fake()->randomElement( [ false, true ] ),
            'created_at' => now(),
            'deleted_at' =>  fake()->randomElement( [now(), null] )
        ];
    }
}
