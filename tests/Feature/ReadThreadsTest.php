<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_browse_all_threads()
    {
        $this->get('/threads')
             ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_browse_a_single_thread()
    {
        $this->get($this->thread->path())
             ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug )
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_username()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);
        $threadByJohn = create('App\Thread', ['user_id' => $john->id]);
        $threadNotByJohn = create('App\Thread');

        $this->get('/threads/?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id'=>$threadWithThreeReplies->id], 3);

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id'=>$threadWithTwoReplies->id], 2);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('/threads/?popular=1')->json();

        $count = array_column( $response, 'replies_count' );

        $this->assertEquals([3, 2, 0], $count);
    }

    /** @test */
    function when_orderby_popularity_threads_with_same_popularity_are_ordered_by_latest()
    {
        $thread_old = create('App\Thread', ['created_at' => Carbon::yesterday()]);
        create('App\Reply', ['thread_id' => $thread_old->id], 2);

        $thread_new = create('App\Thread', ['created_at' => Carbon::now()]);
        create('App\Reply', ['thread_id' => $thread_new->id], 2);

        $threadNoReply = $this->thread;

        $response = $this->getJson('/threads/?popular=1')->json();

        $ids = array_column($response, 'id');

        $this->assertEquals([$thread_new->id, $thread_old->id, $threadNoReply->id], $ids);
    }
}
