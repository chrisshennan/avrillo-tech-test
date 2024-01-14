<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Manager\QuoteManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class QuoteController
 */
class QuoteController extends Controller
{
    /**
     * @param QuoteManager $quoteManager
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function show(QuoteManager $quoteManager): JsonResponse
    {
        return response()->json($quoteManager->getQuotes(5));
    }

    /**
     * @param QuoteManager $quoteManager
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function refresh(QuoteManager $quoteManager): JsonResponse
    {
        $quoteManager->refreshQuotes();

        return response()->json(['status' => 'success']);
    }
}
