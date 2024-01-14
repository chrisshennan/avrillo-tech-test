<?php

namespace App\Http\Service\QuoteApi;

use App\Http\Collection\QuoteCollection;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractQuoteService
{
    /**
     * @return self
     */
    abstract protected function retrieve(): self;

    /**
     * @param ResponseInterface $response
     * @return self
     */
    abstract protected function transform(ResponseInterface $response): self;

    /**
     * @return QuoteCollection
     */
    abstract public function getQuotes(): QuoteCollection;
}
