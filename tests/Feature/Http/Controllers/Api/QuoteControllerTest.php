<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuoteControllerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api/quotes/show', [
            'x-api-key' => 'this-is-a-valid-api-key',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonIsArray();
        $response->assertJsonCount(5);
    }
}
