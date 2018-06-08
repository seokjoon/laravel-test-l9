<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AttachmentsTableSeeder extends Seeder
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

    	\App\Attachment::truncate();
    	if(!(File::isDirectory(attachments_path()))) {
			File::makeDirectory(attachments_path(), 775, true);
		}
		File::cleanDirectory(attachments_path());

		$this->command->error('Downloading images from lorempixel. It takes time...');
		$articles->each(function ($article) use ($faker) {
			$path = $faker->image(attachments_path());
			$filename = File::basename($path);
			$bytes = File::size($path);
			$mime = File::mimeType($path);
			$this->command->warn("File saved: {$filename}");

			$article->attachments()->save(factory(\App\Attachment::class)->make(compact('filename', 'bytes', 'mime')));
		});
		$this->command->info('Seeded: attachments table and files');
    }
}
