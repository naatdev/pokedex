<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PokeApiService;

class HomePokemonController extends AbstractController
{
    #[Route('/', name: 'app_home_pokemon')]
    public function index(PokeApiService $pokeApiService): Response
    {
        // get pokemon species for a specific generation
        $pokemon_species = $pokeApiService->getPokemonsByGeneration($_ENV['POKE_GENERATION']);

        // render view with pokemon species and geenration number
        return $this->render('home_pokemon/index.html.twig', [
            'generation_number' => $_ENV['POKE_GENERATION'],
            'pokemon_species'   => $pokemon_species,
        ]);
    }

    #[Route('/pokemon/{id}', name: 'pokemon_details')]
    public function details($id, PokeApiService $pokeApiService): Response
    {
        // get pokemon species for a specific generation
        $pokemon = $pokeApiService->getPokemonDetails($id);

        // render view with pokemon species and geenration number
        return $this->render('home_pokemon/pokemon_details.html.twig', [
            'pokemon_id'        => $id,
            'pokemon'           => $pokemon,
        ]);
    }
}
