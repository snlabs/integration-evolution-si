<?php

namespace App\Controller;

use App\Entity\RegionalAdvisory;
use App\Repository\RegionalAdvisoryRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;

final class RegionalAdvisoryController extends AbstractController{
  
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
        private LoggerInterface $logger
    ){} 
    #[OA\Get(
        summary: 'Récupérer la liste des conseillers regionaux',
        description: 'Cette API permet de récupérer la liste des conseillers regionaux.'
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Numéro de la page',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Nombre d\'éléments par page',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 10)
    )]

    #[Route('/api/regional-advisories', name: 'regional_advisories_list', methods:['GET'])]
    public function getRegionList(Request $request, RegionalAdvisoryRepository $regionalAdvisoryRepository): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);
        $regionalAdvisoriesList = $regionalAdvisoryRepository->findAllWithPagination($page, $limit); 
        $regions =  $regionalAdvisoryRepository->findAll();
    
        $context = SerializationContext::create()->setGroups(['regional_advisory_list']);
        $jsonRegions = $this->serializer->serialize($regionalAdvisoriesList, 'json', $context);
       
        return new JsonResponse($jsonRegions, Response::HTTP_OK, [], true);
    }

    #[Route('/api/regional-advisories/{id}', requirements: ['id' => Requirement::DIGITS], name: 'regional_advisories_show' , methods: ['GET'])]
    public function getDetailRegionalAdvisory(RegionalAdvisory $regionalAdvisory): JsonResponse
    {
   
        $context = SerializationContext::create()->setGroups(['regional_advisory_show']);
        $jsonRegionalAdvisory = $this->serializer->serialize($regionalAdvisory, 'json', $context);
       
        return new JsonResponse($jsonRegionalAdvisory, Response::HTTP_OK, [], true);
    }
 
    #[OA\Post(
        summary: 'Créer un conseil régional ',
        description: 'Cette route permet d\'ajouter un conseil régional avec les informations nécessaires.'
    )]
    #[OA\RequestBody(
        description: 'Données d un\'conseil régional à créer',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'mandature', type: 'string'),
                new OA\Property(property: 'civilite', type: 'string'),
                new OA\Property(property: 'prenom', type: 'string'),
                new OA\Property(property: 'nom', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Conseil régional créé avec succès',
    )]
    #[Route('/api/regional-advisories', name:"createRegionalAdvisory", methods: ['POST'])]
    public function createRegionalAdvisory(Request $request, RegionRepository $regionRepository): JsonResponse 
    {
        try {

            $data = json_decode($request->getContent(), true);
            $regionCode  = $data['code_dep_election'] ?? -1;
            $region = $regionRepository->find($regionCode);

            if(!$region) {
                return new JsonResponse(['error' => 'Region not found'], Response::HTTP_NOT_FOUND);  
            }

            $regionalAdvisory = $this->serializer->deserialize($request->getContent(), RegionalAdvisory::class, 'json');

            $regionalAdvisory->setRegion($region);

            $this->entityManager->persist($regionalAdvisory);
            $this->entityManager>flush();


            $context = SerializationContext::create()->setGroups(['regional_advisory_show']);
            $jsonRegionalAdvisory = $this->serializer->serialize($regionalAdvisory, 'json', $context);

        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

 
        return new JsonResponse($jsonRegionalAdvisory, Response::HTTP_CREATED, [], true);
   }

 
    #[Route('/api/regional-advisories/{id}', name:"updateRegionalAdvisory", methods:['PUT'])]

    public function updateRegionalAdvisory(Request $request, SerializerInterface $serializer, RegionalAdvisory $currentRegionalAdvisory): JsonResponse 
    {
        
        $data = json_decode($request->getContent(), true);

        $currentRegionalAdvisory->setProfession( $data['profession'] ?? null);
        $currentRegionalAdvisory->setMail( $data['mail'] ?? null);
        
        $this->entityManager->persist($currentRegionalAdvisory);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

    }
    
    #[Route('/api/regional-advisories/{id}', name: 'deleteRegionalAdvisory', methods: ['DELETE'])]
    public function deleteRegionalAdvisory(RegionalAdvisory $regionalAdvisory): JsonResponse 
    {
        $this->entityManager->remove($regionalAdvisory);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Suppression effectuée avec succès'], Response::HTTP_NO_CONTENT);
    }
}
