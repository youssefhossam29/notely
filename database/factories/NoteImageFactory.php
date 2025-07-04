<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NoteImage>
 */
class NoteImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Generate fake image using Faker
        $image = fake()->image(
            dir: public_path('uploads/notes/'),
            width: 200,
            height: 200,
            category: null,
            fullPath: false,
            randomize: false,
            word: fake()->word,
            gray: false,
            format: 'png'
         );

        return [
            //
            'name' =>  $image ,
            'created_at' => now(),
        ];
    }
}
