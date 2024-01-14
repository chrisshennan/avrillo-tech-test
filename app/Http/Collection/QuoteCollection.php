<?php

namespace App\Http\Collection;

use App\Http\Dto\Quote;

/**
 * Class QuoteCollection
 */
class QuoteCollection
{
    /**
     * @var Quote[]
     */
    protected array $quotes = [];

    /**
     * @param string $quote
     * @param string $author
     * @return $this
     */
    public function addQuote(string $quote, string $author): self
    {
        // Convert the raw data our own data format
        // If we were to integration with multiple APIs this will allow us to ensure the data is consistent
        // when passed through our application and out logic doesn't have to be concerned with where it came from
        // and their own particular data formats.

        $quoteObject = new Quote();
        $quoteObject->setQuote($quote);
        $quoteObject->setAuthor($author);

        $this->quotes[] = $quoteObject;

        return $this;
    }

    /**
     * @return Quote[]
     */
    public function getQuotes(): array
    {
        return $this->quotes;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $output = [];
        foreach ($this->getQuotes() as $quote)
        {
            $output[] = [
                'quote' => $quote->getQuote(),
                'author' => $quote->getAuthor(),
            ];
        }

        return $output;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->quotes);
    }
}
