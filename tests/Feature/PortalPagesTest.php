<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan database seeders untuk menyiapkan data awal
        $this->seed();
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIRITA');
    }

    public function test_search_page_loads_successfully(): void
    {
        $response = $this->get('/cari?q=Kemenag');
        $response->assertStatus(200);
        $response->assertSee('Hasil untuk');
    }

    public function test_news_detail_page_loads_successfully(): void
    {
        $post = Post::published()->first();
        $this->assertNotNull($post);

        $response = $this->get(route('posts.show', $post));
        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_category_page_loads_successfully(): void
    {
        $category = Category::where('is_active', true)->whereHas('posts', fn($q) => $q->published())->first();
        $this->assertNotNull($category);

        $response = $this->get(route('categories.show', $category));
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_unit_page_loads_successfully(): void
    {
        $unit = Unit::where('is_active', true)->whereHas('posts', fn($q) => $q->published())->first();
        $this->assertNotNull($unit);

        $response = $this->get(route('units.show', $unit));
        $response->assertStatus(200);
        $response->assertSee($unit->name);
    }

    public function test_like_post_endpoint_has_rate_limiting(): void
    {
        $post = Post::published()->first();
        $this->assertNotNull($post);

        // Panggilan 1 - 5 harus sukses dialihkan kembali (status 302)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('posts.like', $post));
            $response->assertStatus(302);
        }

        // Panggilan ke-6 dalam kurun waktu 1 menit harus mengembalikan HTTP 429 (Too Many Requests)
        $response = $this->post(route('posts.like', $post));
        $response->assertStatus(429);
    }

    public function test_feed_loads_successfully(): void
    {
        $response = $this->get('/feed');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('SIRITA - Kemenag Tana Toraja');

        $post = Post::published()->first();
        if ($post) {
            $response->assertSee($post->title);
        }
    }
}
