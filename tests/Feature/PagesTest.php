<?php

namespace Tests\Feature;

use App\Models\Pages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function test_page_front()
    {
        $page = Pages::factory()->create();

        $this->get('/'. $page->slug)->assertStatus(404);

        $page->status = Pages::STATUS_ACTIVE;
        $page->save();

        $response = $this->get('/' . $page->slug)
            ->assertStatus(200)
            ->assertSee($page->title);

        //dd($response->getContent());

        $page->fill([
            
        ]);
    }
}
