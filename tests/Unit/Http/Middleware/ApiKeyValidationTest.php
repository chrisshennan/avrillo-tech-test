<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ApiKeyValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApiKeyValidationTest extends TestCase
{
    /**
     * Test the handle method for valid API key
     * @return void
     */
    public function testHandleValid(): void
    {
        // Mock closure to handle the $next($request) call
        $closure = function (Request $request) {
            return new Response();
        };

        $request = new Request();
        $request->headers->set('x-api-key', 'this-is-a-valid-api-key');

        $middleware = new ApiKeyValidation($request, $closure);

        $response = $middleware->handle($request, $closure);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test the handle method for invalid API keys
     * @dataProvider dataProviderForTestHandleInvalid
     * @param Request $request
     * @param string $expected
     * @return void
     */
    public function testHandleInvalid(Request $request, string $expected): void
    {
        // Mock closure to handle the $next($request) call
        $closure = function (Request $request) {
            return new Response();
        };

        $middleware = new ApiKeyValidation($request, $closure);

        $response = $middleware->handle($request, $closure);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals([ 'message' => $expected], json_decode($response->getContent(), true));
    }

    public static function dataProviderForTestHandleInvalid()
    {
        $noApiKey = new Request();

        $invalidApiKey = new Request();
        $invalidApiKey->headers->set('x-api-key', 'invalid');

        return [
            'no-api-key' => [
                $noApiKey,
                'Unauthorized'
            ],
            'invalid-api-key' => [
                $invalidApiKey,
                'Unauthorized'
            ],
        ];
    }
}
