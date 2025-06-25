<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Note;
use App\Models\Profile;
use App\Models\NoteImage;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 users and create 5 notes foreach user
        User::factory(5)->create()
            ->each(function ($user){
                Note::factory(5)
                ->create(['user_id' => $user->id])
                    ->each(function ($note){
                    NoteImage::factory(2)
                    ->create(['note_id' => $note->id]);
                });
            })
            ->each(function ($user){
                Profile::factory(1)
                ->create(['user_id' => $user->id]);
            });
    }
}
