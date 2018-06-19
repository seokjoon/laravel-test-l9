<?php

namespace App\Transformers;

use App\Tag;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class TagTransformer extends TransformerAbstract
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
     * @param  \App\Tag $tag
     * @return  array
     */
    public function transform(Tag $tag)
    {
        $payload = [
            'id' => (int) $tag->id,
            // ...
            'created' => $tag->created_at->toIso8601String(),
            'link' => [
                 'rel' => 'self',
                 'href' => route('api.v1.tags.show', $tag->id),
            ],
        ];

        return $this->buildPayload($payload);
    }

        }
