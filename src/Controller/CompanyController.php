<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/company', name: 'company_api')]
class CompanyController extends AbstractFOSRestController
{
    public array $companySerializerConfig = [
        AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
        AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
        'groups' => [
            'list_company',
            'list_user'
        ]
    ];
    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer)
    {
    }

    #[Rest\Get('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $companies = $this->serializer->serialize($this->companyRepository->findAll(), 'json', $this->companySerializerConfig);
        return new Response($companies, Response::HTTP_OK);
    }

    #[Rest\Get('/{id}', name: 'show', methods: ['GET'])]
    public function show(Company $company) : Response
    {
        $data = $this->serializer->serialize($company, 'json', $this->companySerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Put('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Company $company, Request $request) : Response
    {
        $requestContent = $request->getContent();
        $updatedCompany = $this->serializer->deserialize($requestContent, Company::class, 'json');

        $company->setName($updatedCompany->getName());
        $company->setAddress($updatedCompany->getAddress());

        $this->entityManager->flush();

        return new Response('Company updated.', Response::HTTP_NO_CONTENT);
    }

    #[Rest\Post('/', name: 'create', methods: ['POST'])]
    public function create(Request $request) : Response
    {
        $company = new Company();
        $form = $this->createForm(Company::class, $company);

        $data = $this->serializer->deserialize($request->getContent(), Company::class, 'json');
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->entityManager;
            $em->persist($company);
            $em->flush();
            return new Response('Company created.', Response::HTTP_CREATED);
        }
        return new Response('Company not created.', Response::HTTP_BAD_REQUEST);
    }

    #[Rest\Delete('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Company $company) : Response
    {
        foreach ($company->getUsers() as $user) {
            $user->setCompany(null);
        }
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        return new Response('Company deleted.', Response::HTTP_OK);
    }

    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: ['GET'])]
    public function findById(int $id) : Response
    {
        $company = $this->companyRepository->find($id);

        if (null === $company) {
            return new Response('Companies not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($company, 'json', $this->companySerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
