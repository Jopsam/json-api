<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Index
     * 
     * @test
     */
    public function can_fetch_all_articles()
    {
        $articles = Article::factory()->count(10)->create();
        $response = $this->withoutExceptionHandling()->getJson(route('api.v1.articles.index'));

        $articlesJsonData = collect([]);
        foreach ($articles as $article) {
            $articlesJsonData->push([
                'type'       => 'articles',
                'id'         => (string) $article->getRouteKey(),
                'attributes' => [
                    'title'   => $article->title,
                    'slug'    => $article->slug,
                    'content' => $article->content,
                ],
                'links'      => [
                    'self' => route('api.v1.articles.show', $article),
                ]
            ]);
        }

        $response->assertExactJson([
            'data'  => $articlesJsonData->all(),
            'links' => [
                'self' => route('api.v1.articles.index'),
            ],
        ]);
    }

    /**
     * Show
     * 
     * @test
     */
    public function can_fetch_a_single_article()
    {
        $article = Article::factory()->create();
        $response = $this->withoutExceptionHandling()->getJson(route('api.v1.articles.show', $article));
        $response->assertExactJson([
            'data' => [
                'type'       => 'articles',
                'id'         => (string) $article->getRouteKey(),
                'attributes' => [
                    'title'   => $article->title,
                    'slug'    => $article->slug,
                    'content' => $article->content,
                ],
                'links'      => [
                    'self' => route('api.v1.articles.show', $article),
                ]
            ],
        ]);
    }
}
