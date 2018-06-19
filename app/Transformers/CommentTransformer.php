<?php

namespace App\Transformers;

use App\Comment;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class CommentTransformer extends TransformerAbstract
{
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
     * Transform single resource.
     *
     * @param  \App\Comment $comment
     * @return  array
     */
    public function transform(Comment $comment)
    {
        $payload = [
            'id' => (int) $comment->id,
            // ...
            'created' => $comment->created_at->toIso8601String(),
            'link' => [
                 'rel' => 'self',
                 'href' => route('api.v1.comments.show', $comment->id),
            ],
        ];

        return $this->buildPayload($payload);
    }

        }
