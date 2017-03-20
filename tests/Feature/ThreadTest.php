<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_all_threads()
    {
        $thread_one = factory('App\Thread')->create();
        $thread_twee = factory('App\Thread')->create();

        $response = $this->get('/threads');

        $response->assertStatus(200);
        $response->assertSee($thread_one->title);
        $response->assertSee($thread_twee->title);
    }

    /** @test */
    function a_user_can_browse_a_single_thread()
    {
        $thread = factory('App\Thread')->create();

        $response = $this->get('/threads/' . $thread->id);
        $response->assertStatus(200);
        $response->assertSee($thread->title);
    }
}
