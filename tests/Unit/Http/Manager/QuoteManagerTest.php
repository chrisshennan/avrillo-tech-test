<?php

namespace Tests\Unit\Http\Manager;

use App\Http\Collection\QuoteCollection;
use App\Http\Manager\QuoteManager;
use App\Http\Service\QuoteApi\KanyeRestQuoteService;
use GuzzleHttp\Exception\GuzzleException;
use Mockery;
use Tests\TestCase;

class QuoteManagerTest extends TestCase
{
    /**
     * @return void
     * @throws GuzzleException
     */
    public function testGetQuotesWithCache()
    {
        $cachedQuoteCollection = new QuoteCollection();
        $cachedQuoteCollection->addQuote('test quote 1', 'Kanye West');

        $service = Mockery::mock(KanyeRestQuoteService::class);
        $service
            ->shouldReceive('getQuotes')
            ->once()
            ->with(1)
            ->andReturn($cachedQuoteCollection);

        $service->shouldNotReceive('refresh');

        $manager = new QuoteManager($service);
        $actual = $manager->getQuotes(1);

        $expected = [
            [
                'quote' => 'test quote 1',
                'author' => 'Kanye West',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testGetQuotesWithoutCache()
    {
        $cachedQuoteCollection = new QuoteCollection();

        $refreshedQuoteCollection = new QuoteCollection();
        $refreshedQuoteCollection->addQuote('test quote 1', 'Kanye West');

        $service = Mockery::mock(KanyeRestQuoteService::class);
        $service
            ->shouldReceive('getQuotes')
            ->once()
            ->with(1)
            ->andReturn($cachedQuoteCollection);

        $service
            ->shouldReceive('refresh')
            ->once()
            ->with(1)
            ->andReturn($refreshedQuoteCollection)
        ;

        $manager = new QuoteManager($service);
        $actual = $manager->getQuotes(1);

        $expected = [
            [
                'quote' => 'test quote 1',
                'author' => 'Kanye West',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testRefreshQuotes()
    {
        $service = Mockery::mock(KanyeRestQuoteService::class);
        $service
            ->shouldReceive('refresh')
            ->once()
            ->with(5)
            ->andReturn(new QuoteCollection());

        $manager = new QuoteManager($service);
        $actual = $manager->refreshQuotes(5);

        $this->assertEquals($manager, $actual);
    }
}
