<?php

namespace App\Manager;

use App\Entity\Adress;
use App\Entity\Region;
use App\Entity\RegionalAdvisory;
use Doctrine\ORM\EntityManagerInterface;

class MessageManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createMessage(array $data)
    {
       if(!is_null($data['code_dep_election'])){
       
 
        $region = $this->entityManager->getRepository(Region::class)->find($data['code_dep_election']);
    
            if( $region == null ) {
                $region = new Region();
                $region->setId((int)$data['code_dep_election'] ??  uniqid());
                $region->setDepElection($data['dep_election']);
                $this->entityManager->persist($region);
            }
           
            $regionalAdvisory = new RegionalAdvisory();
        
            $regionalAdvisory->setCivilite($data['civilite']);
            $regionalAdvisory->setPrenom($data['prenom']);
            $regionalAdvisory->setNom($data['nom']);
            $regionalAdvisory->setDateNaissance(new \DateTimeImmutable($data['date_naissance']));
            $regionalAdvisory->setFonctionExecutif($data['fonction_executif'] ?? null);
            $regionalAdvisory->setGroupePolitique($data['groupe_politique']);
            $regionalAdvisory->setListeElectorale($data['liste_electorale'] ?? null);
            $regionalAdvisory->setVilleNaissance($data['ville_naissance']?? null);
            $regionalAdvisory->setProfession($data['profession']);
            $regionalAdvisory->setMandature($data['mandature'] ?? null ?? null);
            $regionalAdvisory->setExecutif((bool)$data['executif'] ?? null);
            $regionalAdvisory->setMail($data['mail'] ?? null);
            $regionalAdvisory->setDateDebutMandat(new \DateTimeImmutable($data['date_debut_mandat']) ?? null);
            $regionalAdvisory->setSiteInternet($data['site_internet'] ?? null);
            $regionalAdvisory->setTwitter($data['twitter'] ?? null);
            $regionalAdvisory->setFacebook($data['facebook'] ?? null);
            $regionalAdvisory->setBlog($data['blog'] ?? null);
            $regionalAdvisory->setLinkedin($data['linkedin'] ?? null);


            $region->addRegionalAdvisory($regionalAdvisory);
            
            if(!empty(trim($data['adresse']))) {

                $adress = new Adress();
                $adress->setStreet($data['adresse'] ?? null);
                $adress->setCodePostal($data['code_postal'] ?? null);
                $adress->setVille($data['ville'] ?? null) ;
                $regionalAdvisory->setAdress($adress);
            }

            $this->entityManager->flush();
        
            echo 'Insert into database : ' . $data['prenom'] .' '.$data['nom'] . PHP_EOL;
       }
      
    }
}
