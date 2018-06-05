<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		if(config('database.default') !== 'sqlite') {
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
		}

		App\User::truncate();
		$this->call(UsersTableSeeder::class);

		App\Article::truncate();
		$this->call(ArticlesTableSeeder::class);

		App\Tag::truncate();
		DB::table('article_tag')->truncate();
		$this->call(TagsTableSeeder::class);

		if(config('database.default') !== 'sqlite') {
			DB::statement('SET FOREIGN_KEY_CHECKS=1');
		}
    }
}
