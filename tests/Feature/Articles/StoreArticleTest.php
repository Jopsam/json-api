<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Store article test
     * 
     * @test
     */
    public function can_store_articles()
    {
        $response = $this->withoutExceptionHandling()->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type'       => 'articles',
                'attributes' => [
                    'title'   => 'Nuevo Articulo',
                    'slug'    => 'nuevo-articulo',
                    'content' => 'Contenido del articulo',
                ],
            ],
        ]);
        $response->assertCreated();
        $article = Article::first();
        $response->assertHeader('Location', route('api.v1.articles.show', $article));
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id'   => $article->getRouteKey(),
                'attributes' => [
                    'title'   => 'Nuevo Articulo',
                    'slug'    => 'nuevo-articulo',
                    'content' => 'Contenido del articulo',
                ],
                'links'      => [
                    'self' => route('api.v1.articles.show', $article),
                ]
            ]
        ]);
    }

    /**
     * Title is required test
     * 
     * @test
     */
    public function title_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type'       => 'articles',
                'attributes' => [
                    'slug'    => 'nuevo-articulo',
                    'content' => 'Contenido del articulo',
                ],
            ],
        ]);
        $response->assertJsonValidationErrors('data.attributes.title');
    }

    /**
     * Slug is required test
     * 
     * @test
     */
    public function slug_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type'       => 'articles',
                'attributes' => [
                    'title'   => 'Nuevo Articulo',
                    'content' => 'Contenido del articulo',
                ],
            ],
        ]);
        $response->assertJsonValidationErrors('data.attributes.slug');
    }

    /**
     * Content is required test
     * 
     * @test
     */
    public function content_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type'       => 'articles',
                'attributes' => [
                    'title'   => 'Nuevo Articulo',
                    'slug'    => 'nuevo-articulo',
                ],
            ],
        ]);
        $response->assertJsonValidationErrors('data.attributes.content');
    }
}
