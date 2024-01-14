<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            [
                'quote' => 'The best preparation for tomorrow is doing your best today.',
                'author' => 'H. Jackson Brown, Jr.',
            ],
            [
                'quote' => 'The best preparation for tomorrow is doing your best today.',
                'author' => 'H. Jackson Brown, Jr.',
            ],
            [
                'quote' => 'The best preparation for tomorrow is doing your best today.',
                'author' => 'H. Jackson Brown, Jr.',
            ],
            [
                'quote' => 'The best preparation for tomorrow is doing your best today.',
                'author' => 'H. Jackson Brown, Jr.',
            ],
            [
                'quote' => 'The best preparation for tomorrow is doing your best today.',
                'author' => 'H. Jackson Brown, Jr.',
            ],
        ]);
    }

    public function refresh()
    {

    }
}
