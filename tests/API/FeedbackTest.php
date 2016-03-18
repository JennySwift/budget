<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FeedbackTest
 */
class FeedbackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * For communicating with my lists app
     * @test
     * @return void
     */
    public function it_can_submit_feedback()
    {
        DB::beginTransaction();
        $this->logInUser();

        $feedback = [
            'title' => 'koala',
            'body' => 'kangaroo',
            'priority' => 2
        ];

        $response = $this->call('POST', '/api/feedback', $feedback);
        $content = json_decode($response->getContent(), true);
     // dd($content);

        $this->assertEquals('koala', $content['title']);
        $this->assertEquals('kangaroo', $content['body']);
        $this->assertEquals('2', $content['priority']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }
}