<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /** 
     * Show all articles
     * 
     * @param Request $request
     * @return ArticleCollection
     */
    public function index(Request $request): ArticleCollection
    {
        return new ArticleCollection(Article::all());
    }

    /** 
     * Store an article
     * 
     * @param Request $request
     * @return ArticleResource
     */
    public function store(Request $request): ArticleResource
    {
        $request->validate([
            'data.attributes.title'    => ['required'],
            'data.attributes.slug'     => ['required'],
            'data.attributes.content'  => ['required'],
        ]);
        $article = Article::create([
            'title'   => $request->input('data.attributes.title'),
            'slug'    => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content'),
        ]);
        return new ArticleResource($article); 
    }

    /** 
     * Show a single article
     * 
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

}
