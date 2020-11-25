<?php

namespace common\services;

use GuzzleHttp\Client;

class BookCatalogService
{
    const BOOKS_REQUEST_TYPE = 'books';
    const AUTHORS_REQUEST_TYPE = 'authors';
    const BOOKS_BY_AUTHOR_REQUEST_TYPE = 'authorbooks';
    /**
     * client for sending GET requests to API server
     * @var Client $client
     */
    private $client;

    /**
     * url to API location
     * @var $api_address string
     */
    private $api_url;

    public function __construct($url = '')
    {
        $this->client = new Client();
        $this->api_url = $url ? $url : 'http://94.254.0.188:4000';
    }

    /**
     * get url to API
     * @return string
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }

    /**
     * @param int $limit specific rows limit
     * @param int $offset specific starting offset
     * @return array books collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBooks($limit = 0, $offset = 0)
    {
        $response = $this->client->get($this->api_url.'/books?limit='.$limit.'&offset='.$offset);
        return $this->getBodyData(json_decode($response->getBody()), self::BOOKS_REQUEST_TYPE);
    }

    /**
     * @param int $limit specific rows limit
     * @param int $offset specific starting offset
     * @return array authors collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthors($limit = 0, $offset = 0)
    {
        $response = $this->client->get($this->api_url.'/authors?limit='.$limit.'&offset='.$offset);
        return $this->getBodyData(json_decode($response->getBody()), self::AUTHORS_REQUEST_TYPE);
    }

    /**
     * @param int $authorId id for specific author
     * @param int $limit specific rows limit
     * @param int $offset specific starting offset
     * @return array books collection by specified author
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBooksByAuthor($authorId, $limit = 0, $offset = 0)
    {
        $response = $this->client->get($this->api_url.'/authors/'.$authorId.'/books?limit='.$limit.'&offset='.$offset);
        return $this->getBodyData(json_decode($response->getBody()), self::BOOKS_BY_AUTHOR_REQUEST_TYPE);
    }

    /**
     * @param string $authorName specified author name
     * @return int|null returns author id if author search match, null otherwise
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthorId($authorName)
    {
        $authors = $this->getAuthors();
        foreach ($authors as $author) {
            if($author->name == $authorName) {
                return $author->id;
            }
        }

        return null;
    }

    /**
     * @param \stdClass $body response body
     * @param $type self classified request type
     * @return array specific data for request type, empty array if data not found
     */
    protected function getBodyData($body, $type)
    {
        if(!empty($body->data)) {
            switch ($type) {
                case self::BOOKS_REQUEST_TYPE:
                case self::BOOKS_BY_AUTHOR_REQUEST_TYPE:
                    return $body->data->books;
                case self::AUTHORS_REQUEST_TYPE:
                    return $body->data->authors;
            }
        }

        return [];
    }
}