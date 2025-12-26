<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TaskParser;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('tr_TR');
        $text = '';

        for ($i = 1; $i <= 30; $i++) {
            $title = $faker->sentence(6); // 6 kelimelik başlık
            $description = $faker->paragraph(3); // 3 cümlelik açıklama
            $extraDetail = $faker->optional(0.7)->paragraph(2); // %70 ihtimalle ek detay

            $text .= "$i-$title\n";
            $text .= "$description\n";
            if ($extraDetail) {
                $text .= "$extraDetail\n";
            }
            $text .= "\n"; // Her görev arası boşluk
        }

        // Create a default project if not exists, or get the first one
        $user = \App\Models\User::first();
        $project = \App\Models\Project::firstOrCreate(
            ['name' => 'Sindi Main'],
            [
                'description' => 'Main project for Sindi tasks',
                'created_by' => $user ? $user->id : 1, // Fallback to 1 if no user
            ]
        );

        // Attach user to project if not already attached
        if ($user && !$project->users()->where('user_id', $user->id)->exists()) {
            $project->users()->attach($user->id, ['role' => 'admin']);
        }

        $parser = new TaskParser();
        $parser->parseAndCreateTasks($text, $project->id);
    }
}
