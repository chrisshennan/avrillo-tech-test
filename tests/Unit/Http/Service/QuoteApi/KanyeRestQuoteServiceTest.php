<?php

namespace Tests\Unit\Http\Service\QuoteApi;

use App\Http\Collection\QuoteCollection;
use App\Http\Service\QuoteApi\KanyeRestQuoteService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

/**
 * Class KanyeRestQuoteServiceTest
 */
class KanyeRestQuoteServiceTest extends TestCase
{
    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetQuotesWithoutCache()
    {
        $quoteCollection = new QuoteCollection();
        $quoteCollection->addQuote('test quote 1', 'Kanye West');
        $quoteCollection->addQuote('test quote 2', 'Kanye West');
        $quoteCollection->addQuote('test quote 3', 'Kanye West');
        $quoteCollection->addQuote('test quote 4', 'Kanye West');
        $quoteCollection->addQuote('test quote 5', 'Kanye West');

        $guzzleClient = Mockery::mock(Client::class);
        $guzzleClient
            ->shouldReceive('request')
            ->with('GET', 'https://api.kanye.rest')
            ->andReturnValues([
                new Response(200, ['Content-Type' => 'application/json'], json_encode([ 'quote' => 'test quote 1' ])),
                new Response(200, ['Content-Type' => 'application/json'], json_encode([ 'quote' => 'test quote 2' ])),
                new Response(200, ['Content-Type' => 'application/json'], json_encode([ 'quote' => 'test quote 3' ])),
                new Response(200, ['Content-Type' => 'application/json'], json_encode([ 'quote' => 'test quote 4' ])),
                new Response(200, ['Content-Type' => 'application/json'], json_encode([ 'quote' => 'test quote 5' ])),
            ])
        ;

        $service = new KanyeRestQuoteService($guzzleClient);

        Cache::shouldReceive('get')
            ->once()
            ->with('api.kanye.rest.quotes')
            ->andReturn(null)
        ;

        Cache::shouldReceive('put')
            ->once()
            ->withSomeOfArgs('api.kanye.rest.quotes')
        ;

        // Test the getQuotes method
        $actual = $service->getQuotes();

        // Check the quote collection has the correct data
        $this->assertEquals($quoteCollection, $actual);
    }
    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetQuotesWithCache()
    {
        $quoteCollection = new QuoteCollection();
        $quoteCollection->addQuote('test quote', 'test author');

        $guzzleClient = Mockery::mock(Client::class);
        $service = new KanyeRestQuoteService($guzzleClient);

        Cache::shouldReceive('get')
            ->once()
            ->with('api.kanye.rest.quotes')
            ->andReturn($quoteCollection)
        ;

        // Test the getQuotes method
        $actual = $service->getQuotes();

        $this->assertEquals($quoteCollection, $actual);
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRefresh()
    {
        Cache::shouldReceive('delete')
            ->once()
            ->with('api.kanye.rest.quotes')
        ;

        $quoteCollection = new QuoteCollection();

        $service = Mockery::mock(KanyeRestQuoteService::class)
            ->makePartial();

        // We test this fully in a separate test so here we just want to ensure it is called
        $service->shouldReceive('getQuotes')
            ->once()
            ->andReturn($quoteCollection)
        ;

        // Test the refresh method
        $actual = $service->refresh();

        $this->assertEquals($quoteCollection, $actual);
    }
}
