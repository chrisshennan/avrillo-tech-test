<?php

namespace App\Http\Dto;

/**
 *
 */
class Quote
{
    /**
     * @var string
     */
    protected string $quote;

    /**
     * @var string
     */
    protected string $author;

    /**
     * @return string
     */
    public function getQuote(): string
    {
        return $this->quote;
    }

    /**
     * @param string $quote
     * @return $this
     */
    public function setQuote(string $quote): self
    {
        $this->quote = $quote;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author): Quote
    {
        $this->author = $author;
        return $this;
    }
}
