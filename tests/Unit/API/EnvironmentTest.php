<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class EnvironmentTest
 */
class EnvironmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_get_the_environment()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/environment/');
        $content = $response->getContent();

        $this->assertEquals('testing', $content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


}