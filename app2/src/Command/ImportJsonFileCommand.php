<?php

namespace App\Command;

use App\Entity\YourEntity; // Remplacez avec votre entité
use App\Message\YourMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportJsonFileCommand extends Command
{
    protected static $defaultName = 'app:import-json-file';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, private MessageBusInterface $messageBus)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import data from a local JSON file and save it to the database')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path to the JSON file')
            ->setHelp('This command allows you to import data from a JSON file and save it into the database.');
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
        // Lire le fichier JSON
        $jsonData = file_get_contents($filePath);
        $dataArray = json_decode($jsonData, true);
   
        if ($dataArray === null) {
            $output->writeln('<error>Invalid JSON file.</error>');
            return Command::FAILURE;
        }
        
        $progressBar = new ProgressBar($output, count($dataArray));
        $progressBar->start();

        // Traiter chaque élément du JSON
        foreach ($dataArray as $i => $data) {
            $output->writeln("Conseil régional - $i: ".$data['prenom']." ".$data['nom'].PHP_EOL);
            $message = new YourMessage(json_encode($data));
            $this->messageBus->dispatch($message);
            $progressBar->advance();
        }

        $progressBar->finish();
        // Sauvegarder les entités dans la base de données
        $output->writeln("");
        $output->writeln('<info>  Data has been successfully imported and saved to the database.</info>');

        return Command::SUCCESS;
    }
}
