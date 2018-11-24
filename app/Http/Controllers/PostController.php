<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wink\WinkPost;
use App\Http\Resources\WinkPost as WinkPostResource;

class PostController extends Controller
{
    /**
     * Retrieves all posts [limit of 10]
     *
     * @return Array
     */
    public function index()
    {
        // TODO: Fix eager loading of select columns
        $posts = WinkPost::where('published', true)
            ->with(['author', 'tags'])
            ->orderBy('publish_date', 'desc')
            ->limit(10) // TODO: Make dynamic for load more button
            ->get();

        return WinkPostResource::collection($posts);
    }

    /**
     * Retrieves all posts related to a category
     *
     * @param String $category - category name
     * @return Array
     */
    public function category($category)
    {
        // Retrieve Wink Tag First
        $tag = \Wink\WinkTag::where('slug', strtolower($category))->first();

        // Get all published posts related to this tag
        $posts = $tag->posts()->where('published', true)->orderBy('publish_date', 'desc')->get();

        return WinkPostResource::collection($posts);
    }

    /**
     * Retrieves all posts related to an author
     *
     * @param String $author_id - Author's slug
     * @return Array
     */
    public function author($author_id)
    {
        $posts = WinkPost::with('tags')
            ->whereHas('author', function($query) use ($author_id){
                $query->where('slug', $author_id);
            })
            ->orderBy('published', true)
            ->limit(10)
            ->get();

        return WinkPostResource::collection($posts);
    }

    /**
     * Retrieves a single post by its slug
     *
     * @param String $slug
     * @return Object
     */
    public function show($slug)
    {
        $post = WinkPost::where('slug', $slug)->first();

        if ($post === null)
        {
            abort(404);
        }

        return WinkPostResource::make($post);
    }

}
