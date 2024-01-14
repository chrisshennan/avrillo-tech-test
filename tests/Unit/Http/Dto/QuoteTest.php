<?php

namespace Tests\Unit\Http\Dto;

use App\Http\Dto\Quote;
use Tests\TestCase;

/**
 * Class QuoteTest
 */
class QuoteTest extends TestCase
{
    /**
     * @return void
     */
    public function testQuoteAccessors()
    {
        $quote = new Quote();
        $quote->setQuote('The best preparation for tomorrow is doing your best today.');

        $this->assertEquals('The best preparation for tomorrow is doing your best today.', $quote->getQuote());
    }

    /**
     * @return void
     */
    public function testAuthorAccessors()
    {
        $quote = new Quote();
        $quote->setAuthor('H. Jackson Brown, Jr.');

        $this->assertEquals('H. Jackson Brown, Jr.', $quote->getAuthor());
    }
}
