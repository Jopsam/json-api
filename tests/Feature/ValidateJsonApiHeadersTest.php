<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Route definition for testing.
        Route::any('test-route', fn() => 'OK')->middleware(ValidateJsonApiHeaders::class);
    }

    /**
     * @test
     */
    public function accept_header_must_be_present_in_all_requests()
    {
        // Test route without sending accept header.
        $this->get('test-route')->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
        // Test route sending accept header.
        $this->get('test-route', [
            'accept' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_in_all_post_requests()
    {
        // Test route without sending content-type header.
        $this->post('test-route', [], [
            'accept' => 'application/vnd.api+json',
        ])->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        // Test route sending content-type header.
        $this->post('test-route', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_in_all_patch_requests()
    {
        // Test route without sending content-type header.
        $this->patch('test-route', [], [
            'accept' => 'application/vnd.api+json',
        ])->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        // Test route sending content-type header.
        $this->patch('test-route', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_in_all_responses()
    {
        // Testing response whith content-type header from 'get' route.
        $this->get('test-route', [
            'accept' => 'application/vnd.api+json',
        ])->assertHeader('content-type', 'application/vnd.api+json');
        // Testing response whith content-type header from 'post' route.
        $this->post('test-route', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type', 'application/vnd.api+json');
        // Testing response whith content-type header from 'patch' route.
        $this->post('test-route', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type', 'application/vnd.api+json');
    }

    /**
     * @test
     */
    public function content_type_header_must_not_be_present_in_empty_responses()
    {
        Route::any('empty-response', fn() => response()->noContent())->middleware(ValidateJsonApiHeaders::class);
        // Testing no content response whithout content-type header from 'get' route.
        $this->get('empty-response', [
            'accept' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');
        // Testing no content response whithout content-type header from 'post' route.
        $this->post('empty-response', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');
        // Testing no content response whithout content-type header from 'patch' route.
        $this->patch('empty-response', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');
        // Testing no content response whithout content-type header from 'delete' route.
        $this->delete('empty-response', [], [
            'accept'       => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');
    }
}
