<?php

namespace App\Command;

use App\Service\KafkaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
 

class ImportedAndPublishedToKafkaCommand extends Command
{
    protected static $defaultName = 'app:imported-published-Kafka';
 
    public function __construct(private KafkaService $kafkaService)
    {
        parent::__construct();
       
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import data from a local CSV file and save it to the KAFKA')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path to the CSV file')
            ->setHelp('This command allows you to import data from a CSV file and save it into the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');
 
        if (!file_exists($filePath)) {
            throw new FileNotFoundException(sprintf('The file "%s" does not exist.', $filePath));
        }
        $output->writeln([
            'Regional Advisories Import',
            '============',
            '',
        ]);

        $jsonData = $this->convertCsvToJson($filePath);
         
        $dataArray = json_decode($jsonData, true);
   
        if ($dataArray === null) {
            $output->writeln('<error>Invalid JSON file.</error>');
            return Command::FAILURE;
        }
        
        $progressBar = new ProgressBar($output, count($dataArray));
        $progressBar->start();

        try {
            foreach ($dataArray as $i => $data) {
                
                $this->kafkaService ->send(KafkaService::SEND_MESSAGE_TOPIC, $data);
            
                $progressBar->advance();
            }
        } catch (\Throwable $e) {
            
            $output->writeln(sprintf(
                '<error>Failed to send to messenger with error message : %s</error>',
                $e->getMessage()
            ));

            return Command::FAILURE;
        }

        $progressBar->finish();
      
        $output->writeln("");
        $output->writeln('<info> The data has been successfully imported and published to Kafka.</info>');

        return Command::SUCCESS;
    }

   
    private function convertCsvToJson(string $filePath): string
    {
        $data = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ';');

            if (!$header) {
                throw new \RuntimeException('Invalid CSV format or empty file.');
            }

            // Convertir les clés en minuscules
            $header = array_map(fn($h) => strtolower(trim($h)), $header);

            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                if (count($header) === count($row)) {
                    $row = array_map('trim', $row); // Nettoyer les espaces
                    $data[] = array_combine($header, $row);
                } else {
                    // Ignorer les lignes invalides
                    continue;
                }
            }

            fclose($handle);
        }

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
