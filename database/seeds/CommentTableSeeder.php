<?php

use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
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

		$articles->each(function ($article) {
			$article->comments()->save(factory(App\Comment::class)->make());
			$article->comments()->save(factory(App\Comment::class)->make());
		});

		$articles->each(function($article) use($faker) {
			$commentIds = \App\Comment::pluck('id')->toArray();
			foreach (range(1,5) as $index) {
				$article->comments()->save(
					factory(App\Comment::class)->make([
						'parent_id' => $faker->randomElement($commentIds),
					])
				);
			}
		});

		$this->command->info('Seeded: comments table');
    }
}
