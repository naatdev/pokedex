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

        $response = $response->toArray()['pokemon_species'];
        $pokemons = [];

        foreach($response as $pokemon) {
            // retrieve pokemon id from URL
            $explode_url = explode('/', $pokemon['url']);
            $count = count($explode_url);
            $id = 0;

            // in case of bad http response check if we have an array
            $pos = $count - 2;
            if(isset($explode_url[$pos])) {
                $id = $explode_url[$pos];
            }

            // create new pokemon array item
            $pokemons[] = [
                'id'    => $id,
                'name'  => $pokemon['name'],
            ];
        }

        return $pokemons;
    }
}