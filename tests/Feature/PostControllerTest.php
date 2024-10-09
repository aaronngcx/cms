<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }

    /** @test */
    public function test_it_stores_a_post_successfully()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Test Post Title',
            'description' => 'Test description for the post.',
            'content' => 'This is the content of the test post.',
            'meta_title' => 'This is meta title',
            'meta_description' => 'This is meta description',
            'keywords' => 'This is keywords',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $user->id, // Use the created user's ID
        ];

        // Make a POST request to the store route
        $response = $this->post(route('posts.store'), $postData);

        // Assert the post was created
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Title',
            'description' => 'Test description for the post.',
            'content' => 'This is the content of the test post.',
            'meta_title' => 'This is meta title',
            'meta_description' => 'This is meta description',
            'keywords' => 'This is keywords',
            'status' => 'published',
            'published_at' => $postData['published_at']->toDateTimeString(),
            'created_by' => $user->id, // Ensure this matches the ID of the created user
        ]);

        // Assert the response is a redirect or success
        $response->assertRedirect(route('posts.index'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Make a POST request with missing fields
        $response = $this->post(route('posts.store'), []);

        // Assert the validation errors
        $response->assertSessionHasErrors(['title', 'description', 'content', 'status']);
    }

    /** @test */
    public function it_cannot_store_a_post_without_authentication()
    {
        // Define the post data
        $postData = [
            'title' => 'Test Post Title',
            'description' => 'Test description for the post.',
            'content' => 'This is the content of the test post.',
            'status' => 'published',
            'published_at' => now(),
        ];

        // Make a POST request without authentication
        $response = $this->post(route('posts.store'), $postData);

        // Assert the response is forbidden
        $response->assertStatus(403);
    }
}
