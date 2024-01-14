<?php

namespace App\Http\Service\QuoteApi;

use App\Http\Collection\QuoteCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
class KanyeRestQuoteService extends AbstractQuoteService
{
    protected string $cacheKey = 'api.kanye.rest.quotes';
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var QuoteCollection
     */
    protected QuoteCollection $quoteCollection;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->quoteCollection = new QuoteCollection();
    }

    /**
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function retrieve(): self
    {
        $response = $this->client->request('GET', 'https://api.kanye.rest');

        $this->transform($response);

        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return $this
     */
    protected function transform(ResponseInterface $response): self
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $this->quoteCollection->addQuote($data['quote'], 'Kanye West');

        return $this;
    }

    /**
     * @param int $limit
     * @return QuoteCollection
     * @throws GuzzleException
     */
    public function getQuotes(int $limit = 5): QuoteCollection
    {
        // Do we have an existing quote collection we can return?
        $data = Cache::get($this->cacheKey);
        if (!is_null($data)) {
            return $data;
        }

        // Kanye.rest API only allows 1 quote per request so we need to loop to get more
        // Ideally their API would accept a limit parameter to return more than 1 quote
        for ($i=0;$i<$limit;$i++) {
            $this->retrieve();

            // Sleep for 1 second to avoid rate limiting (not likely required here but is good practice to be
            // be mindful of the impact your integration might have on 3rd parties).
            sleep(1);
        }

        // Cache the results for an hour
        Cache::put($this->cacheKey, $this->quoteCollection, 3600);

        return $this->quoteCollection;
    }

    /**
     * @param int $limit
     * @return QuoteCollection
     * @throws GuzzleException
     */
    public function refresh(int $limit = 5): QuoteCollection
    {
        Cache::delete($this->cacheKey);
        return $this->getQuotes($limit);
    }
}
