<?php

namespace App\Http\Manager;

use App\Http\Service\QuoteApi\KanyeRestQuoteService;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class QuoteManager
 */
class QuoteManager
{
    /**
     * @var KanyeRestQuoteService
     */
    private KanyeRestQuoteService $quoteService;

    /**
     * @param KanyeRestQuoteService $quoteService
     */
    public function __construct(KanyeRestQuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    /**
     * @param int $limit
     * @return array
     * @throws GuzzleException
     */
    public function getQuotes(int $limit = 5): array
    {
        $quoteCollection = $this->quoteService->getQuotes($limit);

        // If it is a cold start i.e. we have no results, then refresh the quotes
        // This will have a performance hit as there is no cache to speed things up.
        if ($quoteCollection->count() == 0) {
            $quoteCollection = $this->quoteService->refresh($limit);
        }

        return $quoteCollection->toArray();
    }

    /**
     * @param int $limit
     * @return $this
     * @throws GuzzleException
     */
    public function refreshQuotes(int $limit = 5): self
    {
        $this->quoteService->refresh($limit);

        return $this;
    }
}
