<?php

namespace App\Command;

use App\Service\CombinationGenerator;
use App\Service\OptionFlattener;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-product-combinations',
    description: 'Run this command to generate product combinations',
)]
class GenerateProductCombinationsCommand extends Command
{
    const string PATH = 'path';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(self::PATH, InputArgument::REQUIRED, 'Path of the product JSON');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generate product combinations');

        $filePath = $input->getArgument(self::PATH);
        $productData = $this->getDataFromJsonFile($filePath);
        $optionFlattener = new OptionFlattener($productData['options']);
        $optionFlattener->flatten();

        $combinationGenerator = new CombinationGenerator($optionFlattener->getProperties(), $optionFlattener->getValues());
        $combinationGenerator->generate();
        $combinations = $combinationGenerator->getCombinations();

        file_put_contents(__DIR__ . '/../../misc/files/product_combo.json', json_encode($combinations, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }

    /**
     * @throws \Exception
     */
    public function getDataFromJsonFile($filePath)
    {
        $jsonData = file_get_contents($filePath);
        if ($jsonData === false) {
            throw new \Exception("Error reading JSON file: $filePath");
        }

        $dataArray = json_decode($jsonData, true);
        if ($dataArray === null) {
            throw new \Exception("Error decoding JSON data: $filePath");
        }

        return $dataArray;
    }
}
