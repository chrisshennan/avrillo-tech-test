<?php

namespace Tests\Feature\Http\Controllers\Api;

use GuzzleHttp\Psr7\Response;
use Mockery;
use Tests\TestCase;

/**
 * Class QuoteControllerTest
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class QuoteControllerTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the guzzle client call to api.kanye.rest
        // so we don't perform live requests during our tests
        $quote = json_encode([
            'quote' => 'Mocking a single kanye.rest quote',
        ]);

        $guzzleClient = Mockery::mock('overload:GuzzleHttp\Client');
        $guzzleClient
            ->shouldReceive('request')
            ->times(5)
            ->with('GET', 'https://api.kanye.rest')
            ->andReturnValues([
                new Response(200, ['Content-Type' => 'application/json'], $quote),
                new Response(200, ['Content-Type' => 'application/json'], $quote),
                new Response(200, ['Content-Type' => 'application/json'], $quote),
                new Response(200, ['Content-Type' => 'application/json'], $quote),
                new Response(200, ['Content-Type' => 'application/json'], $quote),
            ])
        ;
    }

    /**
     * Test retrieving the quotes with a valid API key
     */
    public function test_show_successful_response(): void
    {
        $response = $this->get('/api/quotes/show', [
            'x-api-key' => 'this-is-a-valid-api-key',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonIsArray();
        $response->assertJsonCount(5);
    }

    /**
     * Test retrieving the quotes with an invalid API key
     * @return void
     */
    public function test_show_unsuccessful_response(): void
    {
        $response = $this->get('/api/quotes/show', [
            'x-api-key' => 'INVALID',
        ]);

        $response->assertStatus(401);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertExactJson([
            'message' => 'Unauthorized',
        ]);
    }

    /**
     * Test refreshing the quotes with a valid API key
     * @return void
     */
    public function test_refresh_successful_response(): void
    {
        $response = $this->post('/api/quotes/refresh', [], [
            'x-api-key' => 'this-is-a-valid-api-key',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertExactJson([
            'status' => 'success',
        ]);
    }
}
