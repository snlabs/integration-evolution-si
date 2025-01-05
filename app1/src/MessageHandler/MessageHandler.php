<?php
namespace App\MessageHandler;

use App\Entity\Region;
use App\Entity\RegionalAdvisory;
use App\Message\YourMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class MessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
 
    }

    public function __invoke(YourMessage $message): void
    {   
        $dataArray = json_decode($message->getContent(), true);
       
        $region = $this->entityManager->getRepository(Region::class)->find($dataArray['code_dep_election']);
    
        if( $region == null ) {
            $region = new Region();
            $region->setId((int)$dataArray['code_dep_election']);
            $region->setDepElection($dataArray['dep_election']);
            $this->entityManager->persist($region);
        }
       
 
        $regionalAdvisory = new RegionalAdvisory();
        $regionalAdvisory->setId($dataArray['id']);
        $regionalAdvisory->setCivilite($dataArray['civilite']);
        $regionalAdvisory->setPrenom($dataArray['prenom']);
        $regionalAdvisory->setNom($dataArray['nom']);
        $regionalAdvisory->setDateNaissance(new \DateTimeImmutable($dataArray['date_naissance']));
        $regionalAdvisory->setFonctionExecutif($dataArray['fonction_executif']);
        $regionalAdvisory->setGroupePolitique($dataArray['groupe_politique']);
        $regionalAdvisory->setVilleNaissance($dataArray['ville_naissance']?? 'ville');
        $regionalAdvisory->setProfession($dataArray['profession']);
      
        $region->addRegionalAdvisory($regionalAdvisory);
    
        $this->entityManager->flush();
      
      
        // Traitez le message ici
        echo 'Insert into database : ' . $dataArray['prenom'] .' '.$dataArray['nom'] . PHP_EOL;
    }
}
