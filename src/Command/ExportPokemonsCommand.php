<?php
namespace App\Command;

use App\Service\PokeApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExportPokemonsCommand extends Command
{
    protected static $defaultName = 'pokemons:export-csv';

    public function __construct(HttpClientInterface $client, bool $requirePassword = false)
    {
        $this->requirePassword = $requirePassword;
        $this->client = $client;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $poke_api_service = new PokeApiService($this->client);

        $output->writeln([
            '===========================',
            '  Export CSV des Pokemons  ',
            '===========================',
            '',
        ]);
        
        // open file
        $output_buffer = fopen('export_pokemon.csv', 'w');

        $pokemons = [];
        $pokemons = $poke_api_service->getPokemonsByGeneration($_ENV['POKE_GENERATION']);

        $first = true;
        foreach($pokemons as $pokemon) {
            $pokemon_entity = $poke_api_service->getPokemonDetails($pokemon['id']);
            $pokemon_data = $pokemon_entity->toArrayForCSV();

            if($first) {
                fputcsv($output_buffer, array_keys($pokemon_data));
                $first = !$first;
            }

            fputcsv($output_buffer, $pokemon_data);
        }

        // close file
        fclose($output_buffer);

        $output->writeln('<info>Export effectué!</info>');

        return Command::SUCCESS;
    }
}
