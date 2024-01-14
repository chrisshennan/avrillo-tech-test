<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->headers->get('x-api-key');

        // Check to see if there is an API key set
        if (empty($apiKey)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // and if that value matches the one in the env file
        $validApiKey = config('app.api_key');
        if ($apiKey !== $validApiKey) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // I'm using the return-early-return often approach above to avoid nested if statements.
        // I am also keeping my logic statements as close to the left as possible and limited to one
        // or two continues to avoid `if (condition1 || condition2 || condition3)` statements which can
        // be hard to maintain.
        //
        // The benefit is that the code is easier to read and understand and if the application
        // flow reaches there then I have everything I need to continue.

        return $next($request);
    }
}
