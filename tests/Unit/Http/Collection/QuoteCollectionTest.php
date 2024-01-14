<?php

namespace Tests\Unit\Http\Collection;

use App\Http\Collection\QuoteCollection;
use Tests\TestCase;

class QuoteCollectionTest extends TestCase
{
    /**
     * @return void
     */
    public function testQuoteAccessors()
    {
        $quoteCollection = new QuoteCollection();
        $quoteCollection->addQuote('test quote', 'test author');

        $this->assertCount(1, $quoteCollection->getQuotes());
    }

    /**
     * @return void
     */
    public function testToArray()
    {
        $quoteCollection = new QuoteCollection();
        $quoteCollection->addQuote('test quote', 'test author');

        $this->assertEquals([
            [
                'quote' => 'test quote',
                'author' => 'test author',
            ],
        ], $quoteCollection->toArray());
    }
}
