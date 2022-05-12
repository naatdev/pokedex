<?php
namespace App\Service;

use App\Entity\Pokemon;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

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

    public function getPokemonDetails($pokemon_id): Pokemon
    {
        // call GET pokemon
        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon-species/' . $pokemon_id
        );

        // get response as array and tranform it
        $response = $response->toArray();
        $response['color'] = $response['color']['name'];

        foreach($response['names'] as $name) {
            if($name['language']['name'] == 'fr') {
                $response['name'] = $name['name'];
            }
        }

        $response['legendary'] = $response['is_legendary'];
        $response['mythical'] = $response['is_mythical'];
        $response['happiness'] = $response['base_happiness'];

        // transform array to entity with serializer
        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $serializer = new Serializer([$normalizer]);

        return $serializer->denormalize($response, Pokemon::class);
    }
}