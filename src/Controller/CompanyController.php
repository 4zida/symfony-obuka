<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Util\ContextGroup;
use Doctrine\ORM\EntityManagerInterface;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/api/company/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        return $this->jsonWithGroup($this->companyRepository->findAll(), ContextGroup::COMPANY_INFO);
    }

    #[Route('/api/company/{id}', methods: Request::METHOD_GET)]
    public function show(Company $company) : JsonResponse
    {
        return $this->jsonWithGroup($company, ContextGroup::COMPANY_INFO);
    }

    #[Route('/api/company/{id}', methods: Request::METHOD_PATCH)]
    public function update(Company $company, Request $request) : Response
    {
        $this->handleJSONForm($request, $company, CompanyType::class, [], false);

        $this->entityManager->flush();

        return $this->createOkResponse('Company updated.');
    }

    #[Route('/api/company/', methods: ['POST'])]
    public function create(Request $request) : Response // TODO
    {
        $company = new Company();
        $this->handleJSONForm($request, $company, CompanyType::class);

        $this->entityManager->flush();
        return $this->createOkResponse('Company created.');
    }

    #[Route('/api/company/{id}', methods: ['DELETE'])]
    public function delete(Company $company) : Response
    {
        foreach ($company->getUsers() as $user) {
            $user->setCompany(null);
        }
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        return $this->createOkResponse('Company deleted.');
    }

    #[Route('/api/company/search/{id}', methods: Request::METHOD_GET)]
    public function findById(int $id) : Response
    {
        $company = $this->companyRepository->find($id);

        if (null === $company) {
            return new Response('Companies not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->jsonWithGroup($company, ContextGroup::COMPANY_INFO);
    }
}
