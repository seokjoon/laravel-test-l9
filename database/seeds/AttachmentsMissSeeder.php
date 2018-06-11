<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AttachmentsMissSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$faker = app(Faker\Generator::class);
		$articles = \App\Article::all();

		foreach (range(1, 10) as $index) {
			$path = $faker->image(attachments_path());
			$filename = File::basename($path);
			$bytes = File::size($path);
			$mime = File::mimeType($path);
			$this->command->warn("FILE saved: {$filename}");

			factory(\App\Attachment::class)->create([
				'filename' => $filename,
				'bytes' => $bytes,
				'mime' => $mime,
				'created_at' => $faker->dateTimeBetween('-1 months'),
			]);

        }
    }
}
