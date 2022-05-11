<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPokemonsByGeneration($generation): array
    {
        // call GET generation
        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/generation/' . $generation
        );

        return $response->toArray();
    }
}