<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        echo app()->environment();
    }

    use DatabaseMigrations;

    /** @test */
    function an_unauthenticated_user_may_not_paticipate_in_form_threads()
    {
        $this->expectException(AuthenticationException::class);
        $this->post('threads/some_channel/1/replies', []);
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();

        $this->post( $thread->path() . '/replies', $reply->toArray());

        $this->get( $thread->path() )
             ->assertSee( $reply->body );
    }

    /** @test */
    function a_reply_requires_a_bodya_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function post_to_threads_1_replies_calls_repliesController_store()
    {
//        $this->post( 'threads/1/replies', [])
//             ->assertSee('repliesController1');
    }

    /** @test */
    function post_to_threads_1_replies_calls_Thread_addReply()
    {
//        $this->post( 'threads/1/replies', [])
//            ->assertSee('addReply1');
    }
}
