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
    function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_form_threads_my_version()
    {
        $this->signIn();

        $thread = raw('App\Thread');

        $this->post('/threads', $thread);

        $thread_posted = Thread::latest()->first();
        $url = '/threads/'. $thread_posted->channel->slug . '/' . $thread_posted->id;

        $this->get($url)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);
    }

    /** @test */
    function an_authenticated_user_can_create_new_form_threads_jeffreys_version()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body'=>null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_channel_id()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id'=>null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id'=>3])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function unauthorized_user_may_not_delete_a_thread()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete( $thread->path() )
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete( $thread->path() )
            ->assertRedirect('/login');
    }

    /** @test */
    function a_thread_can_be_deleted_by_the_authorized_user()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $response = $this->json('delete', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [ 'id'=> $thread->id ]);
    }

    /** @test */
    function reply_is_also_deleted_after_a_thread_being_deleted()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('delete', $thread->path());

        $this->assertDatabaseMissing('replies', [ 'id'=> $reply->id ]);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
