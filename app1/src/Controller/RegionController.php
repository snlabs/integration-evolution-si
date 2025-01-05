<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Region;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
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

#[Route('/api')]
class RegionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
        ){} 
    #[Route('/regions', name: 'region_list', methods:['GET'])]
    public function getBookList(RegionRepository $regionRepository): JsonResponse
    {
        $regions =  $regionRepository->findAll();
    
        $context = SerializationContext::create()->setGroups(['regions_list']);
        $jsonRegions = $this->serializer->serialize($regions, 'json', $context);
       
        return new JsonResponse($jsonRegions, Response::HTTP_OK, [], true);
    }

    #[Route('/regions/{id}', requirements: ['id' => Requirement::DIGITS], name: 'region_show' , methods: ['GET'])]
    public function getDetailBook(Region $region): JsonResponse
    {
   
        $context = SerializationContext::create()->setGroups(['regions_list']);
        $jsonBook = $this->serializer->serialize($region, 'json', $context);
       
        return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
      
    }

    #[Route('/regions/{id}/regional-advisories', name: 'region_advisories', methods: ['GET'])]
    public function regionalAdvisories(EntityManagerInterface $em, int $id): JsonResponse
    {
        $region = $em->getRepository(Region::class)->find($id);
        if (!$region) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }
        $regionalAdvisories = $region->getRegionalAdvisories();
        $context = SerializationContext::create()->setGroups(['regions_list']);
        $jsonregionalAdvisories = $this->serializer->serialize($regionalAdvisories, 'json', $context);
    
        return new JsonResponse($jsonregionalAdvisories, 200, [], true);
    }
    

    #[Route('/books', name: 'create_book', methods: ['POST'])]
    public function createBook(Request $request, AuthorRepository $authorRepository): JsonResponse
    {   
 
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json');
        $content = $request->toArray();

        $idAuthor = $content['idAuthor'] ?? -1;
        $book->setAuthor($authorRepository->find($idAuthor));

        $errors = $this->validator->validate($book);
       
        if (count($errors) > 0) {
            return $this->json(['error'=>(string) $errors], Response::HTTP_BAD_REQUEST);
            
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();
         
        return $this->json($book, Response::HTTP_CREATED, [],[
            'groups' => ['books.index','books.show']
        ]);
    }
}
