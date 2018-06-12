<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$tags = config('project.tags');
		foreach (array_transpose($tags) as $slug => $names) {
			\App\Tag::create([
				'name' => $names['ko'],
				'ko' => $names['ko'],
				'en' => $names['en'],
				'slug' => str_slug($slug)
			]);
		}
		$this->command->info('Seeded: tags table');

		$faker = app(Faker\Generator::class);
		$users = \App\User::all();
		$articles = \App\Article::all();
		$tags = \App\Tag::all();
		foreach ($articles as $article) {
			$article->tags()->sync(
				$faker->randomElements(
					$tags->pluck('id')->toArray(), rand(1, 3)
				)
			);
		}
		$this->command->info('Seeded: article_tag table');
    }
}
