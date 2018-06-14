<?php

return [
	'api_domain' => env('API_DOMAIN'),
	//'cache' => false,
	'cache' => true,
	'description' => 'desc',
	'locales' => [
		'en' => 'English',
		'ko' => '한국어',
	],
	'name' => 'l9',
	'sorting' => [
		'view_count' => '조회수',
		'created_at' => '작성일',
	],
	'tags' => [
		'ko' => [
			'laravel' => '라라벨',
			'lumen' => '루멘',
			'general' => '자유의견',
			'server' => '서버',
			'tip' => '팁',
		],
		'en' => [
			'laravel' => 'Laravel',
			'lumen' => 'Lumen',
			'general' => 'General',
			'server' => 'Server',
			'tip' => 'Tip',
		],
	],
	'url' => env('APP_URL'),
];

