<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Hateoas\HateoasBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/api')]
class RegionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ){}

  /**
     * Cette méthode permet de récupérer l'ensemble des regions.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des regions",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Region::class, groups={"regions_list"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     */

    #[Route('/regions', name: 'region_list', methods:['GET'])]
        /* @OA\Response(
    *     response=200,
    *     description="Region the rewards of an user",
    *     @OA\JsonContent(
    *        type="array",
    *        @OA\Items(ref=@Model(type=Region::class, groups={"regions_list"}))
    *     )
    * )
    */
    public function getRegionList(RegionRepository $regionRepository): JsonResponse
    {
        $regions =  $regionRepository->findAll();
        $context = SerializationContext::create()->setGroups(['regions_list']);
        $jsonRegions = $this->serializer->serialize($regions, 'json', $context);
        
        return new JsonResponse($jsonRegions, Response::HTTP_OK, [], true);
    }

    #[Route('/regions/{id}', requirements: ['id' => Requirement::DIGITS], name: 'region_show' , methods: ['GET'])]
    public function getDetailRegion(Region $region): JsonResponse
    {
   
        $context = SerializationContext::create()->setGroups(['regions_list', 'regions_show']);
        $jsonBook = $this->serializer->serialize($region, 'json', $context);
       
        return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
      
    }

    #[Route('/regions/{id}/regional-advisories', name: 'region_advisories', methods: ['GET'])]
    public function regionalAdvisories(EntityManagerInterface $em, int $id): JsonResponse
    {
        $region = $em->getRepository(Region::class)->find($id);
        if (!$region) {
            return new JsonResponse(['error' => 'Region not found'], 404);
        }
        $regionalAdvisories = $region->getRegionalAdvisories();
        $context = SerializationContext::create()->setGroups(['regional_advisory_list']);
        $jsonregionalAdvisories = $this->serializer->serialize($regionalAdvisories, 'json', $context);
    
        return new JsonResponse($jsonregionalAdvisories, 200, [], true);
    }
  
}
