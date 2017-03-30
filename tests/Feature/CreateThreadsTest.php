<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guest_may_not_create_new_form_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads', []);
    }

    /** @test */
    function an_authenticated_user_can_create_new_form_threads_my_version()
    {
        $this->signIn();

        $thread = raw('App\Thread');

        $this->post('/threads', $thread);

        $url = '/threads/' . Thread::latest()->first()->id;

        $this->get($url)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);
    }

    /** @test */
    function an_authenticated_user_can_create_new_form_threads_jeffreys_version()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
