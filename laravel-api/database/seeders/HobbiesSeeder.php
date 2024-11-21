<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HobbiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobbies = [
            ['name' => 'Reading', 'description' => 'Enjoy reading books of all genres.'],
            ['name' => 'Cycling', 'description' => 'Mountain and road cycling for fitness.'],
            ['name' => 'Traveling', 'description' => 'Exploring new places and cultures around the world.'],
            ['name' => 'Photography', 'description' => 'Capturing moments through the lens of a camera.'],
            ['name' => 'Cooking', 'description' => 'Experimenting with new recipes and cuisines.'],
            ['name' => 'Gaming', 'description' => 'Playing video games for entertainment and relaxation.'],
            ['name' => 'Music', 'description' => 'Listening to and playing music on various instruments.'],
            ['name' => 'Swimming', 'description' => 'Swimming as a form of exercise and relaxation.'],
            ['name' => 'Gardening', 'description' => 'Growing plants and maintaining a garden.'],
            ['name' => 'Yoga', 'description' => 'Practicing yoga for mindfulness and flexibility.'],
        ];

        DB::table('hobbies')->insert($hobbies);
    }
}
