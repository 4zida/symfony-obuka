<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Util\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/company', name: 'company_api')]
class CompanyController extends BaseRestController
{
    use FormTrait;

    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer)
    {
    }

    #[Rest\Get('/', name: 'index', methods: Request::METHOD_GET)]
    public function index(): Response
    {
        $companies = $this->serializeJSON($this->companyRepository->findAll(), context: SerializerHelper::COMPANY_CONFIG);
        return $this->generateOkResponse($companies);
    }

    #[Rest\Get('/{id}', name: 'show', methods: Request::METHOD_GET)]
    public function show(Company $company) : Response
    {
        $data = $this->serializer->serialize($company, 'json', SerializerHelper::COMPANY_CONFIG);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Patch('/{id}', name: 'update', methods: Request::METHOD_PATCH)]
    public function update(Company $company, Request $request) : Response
    {
        $this->handleJSONForm($request, $company, CompanyType::class, [], false);

        $this->entityManager->flush();

        return new Response('Company updated.', Response::HTTP_OK);
    }

    #[Rest\Post('/', name: 'create', methods: ['POST'])]
    public function create(Request $request) : Response // TODO
    {
        $company = new Company();
        $this->handleJSONForm($request, $company, CompanyType::class);

        $this->entityManager->flush();
        return new Response('Company created.', Response::HTTP_OK);
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

    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: Request::METHOD_GET)]
    public function findById(int $id) : Response
    {
        $company = $this->companyRepository->find($id);

        if (null === $company) {
            return new Response('Companies not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($company, 'json', SerializerHelper::COMPANY_CONFIG);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
