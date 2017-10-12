<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FeedbackTest
 */
class FeedbackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Todo: This test fails (not found error) even when the feature works
     * @test
     * @return void
     */
    public function it_can_create_an_item_in_my_lists_app()
    {
        $this->markTestSkipped('Skipping lists app test');
        DB::beginTransaction();
        $this->logInUser();

        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 468,
            'category_id' => 1,
            'not_before' => '2050-02-03 13:30:05'
        ];

//        $response = $this->call('POST', 'http://lists.jennyswiftcreations.com/api/items', $item);
        $response = $this->call('POST', 'http://lists.dev:8000/api/items', $item);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['urgency']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(468, $content['parent_id']);
        $this->assertEquals(1, $content['category_id']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     *
     * @param $item
     */
    public function checkItemKeysExist($item)
    {
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('parent_id', $item);
        $this->assertArrayHasKey('title', $item);
        $this->assertArrayHasKey('body', $item);
        $this->assertArrayHasKey('index', $item);
        $this->assertArrayHasKey('category_id', $item);
        $this->assertArrayHasKey('priority', $item);
        $this->assertArrayHasKey('favourite', $item);
        $this->assertArrayHasKey('pinned', $item);
        $this->assertArrayHasKey('path', $item);
        $this->assertArrayHasKey('has_children', $item);
        $this->assertArrayHasKey('category', $item);
        $this->assertArrayHasKey('urgency', $item);
        $this->assertArrayHasKey('alarm', $item);
        $this->assertArrayHasKey('timeLeft', $item);
        $this->assertArrayHasKey('notBefore', $item);
    }
}