<?php

namespace App\Transformers;

use App\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ArticleTransformer extends TransformerAbstract
{
            /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):sort(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = [
        'comments'
    ];

    /**
     * List of resources to be included always.
     *
     * @var  array
     */
    protected $defaultIncludes = [];
        /**
     * List of attributes to respond.
     *
     * @var  array
     */
    protected $visible = [];

    /**
     * List of attributes NOT to respond.
     *
     * @var  array
     */
    protected $hidden = [];

	/**
	 * Include comments.
	 *
	 * @param  \App\Article $article
	 * @param  \League\Fractal\ParamBag|null $paramBag
	 * @return  \League\Fractal\Resource\Collection
	 */
	public function includeComments(Article $article, ParamBag $paramBag = null)
	{
		$transformer = new \App\Transformers\CommentTransformer($paramBag);

		$comments = $article->comments()
			->limit($transformer->getLimit())
			->offset($transformer->getOffset())
			->orderBy($transformer->getSortKey(), $transformer->getSortDirection())
			->get();

		return $this->collection($comments, $transformer);
	}

    /**
     * Transform single resource.
     *
     * @param  \App\Article $article
     * @return  array
     */
    public function transform(Article $article)
    {
        $payload = [
            'id' => (int) $article->id,
			'title' => $article->title,
			'content' => $article->content,
			'content_html' => markdown($article->content),
			'author'       => [
				'name'   => $article->user->name,
				'email'  => $article->user->email,
				'avatar' => 'http:' . gravatar_profile_url($article->user->email),
			],
			'tags'         => $article->tags->pluck('slug'),
			'view_count' => (int) $article->view_count,
			'created' => $article->created_at->toIso8601String(),
			'attachments'  => (int) $article->attachments->count(),
			'comments'     => (int) $article->comments->count(),
			'links' => [
				[
					'rel' => 'self',
					'href' => route('api.v1.articles.show', $article->id),
				],
				[
					'rel' => 'api.v1.articles.attachments.index',
					'href' => route('api.v1.articles.attachments.index', $article->id),
				],
				[
					'rel' => 'api.v1.articles.comments.index',
					'href' => route('api.v1.articles.comments.index', $article->id),
				],
			],
        ];

        /* if($fields = $this->getPartialFields()) {
        	$payload = array_only($payload, $fields);
		} */
        $fields = request()->get('fields');
        if(!(empty($fields))) {
        	$fields = explode(',', $fields);
			$payload = array_only($payload, $fields);
		}
        return $this->buildPayload($payload);
    }
 }
