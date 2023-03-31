<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\CreateBlogPost as BlogPostBase;
use App\Rules\PostImage;
use ProfilanceGroup\BackendSdk\Support\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateBlogPost
{
    /**
     * @param null  $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $validation = app(\Illuminate\Contracts\Validation\Factory::class);
        $validator = $validation->make($args, [
            'image' => ['required', new PostImage()],
        ]);

        if($validator->fails()) {
            return Response::error(null, [
                'validation_errors' => $validator->errors()->getMessages(),
            ]);
        }

        $blog_post = app(BlogPostBase::class)->create($args);

        $blog_post->getMedia('images');

        return Response::success(null, [
            'blog_post' => $blog_post,
        ]);
    }
}