<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Util\ContextGroup;
use App\Util\CustomRequirement;
use App\Util\ResponseMessage;
use Doctrine\ORM\EntityManagerInterface;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/api/company/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        return $this->jsonWithGroup($this->entityManager->getRepository(Company::class)->findAll(),
            ContextGroup::COMPANY_ALL_DETAILS);
    }

    #[Route('/api/company/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function show(Company $company) : JsonResponse
    {
        return $this->jsonWithGroup($company, ContextGroup::COMPANY_ALL_DETAILS);
    }

    #[Route('/api/company/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_PATCH)]
    public function update(Company $company, Request $request) : Response
    {
        $this->handleJSONForm($request, $company, CompanyType::class, [], false);

        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::COMPANY_UPDATED);
    }

    #[Route('/api/company/', methods: Request::METHOD_POST)]
    public function create(Request $request) : Response
    {
        $this->handleJSONForm($request, new Company(), CompanyType::class);

        $this->entityManager->flush();
        return $this->createOkResponse(ResponseMessage::COMPANY_CREATED);
    }

    #[Route('/api/company/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_DELETE)]
    public function delete(Company $company) : Response
    {
        $company->setIsActive(false);
        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::COMPANY_DELETED);
    }

    #[Route('/api/company/search/{id}', requirements: ['id' => Requirement::POSITIVE_INT],
        methods: Request::METHOD_GET)]
    public function findById(int $id) : Response
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);

        if (null === $company) {
            return new Response('Companies not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->jsonWithGroup($company, ContextGroup::COMPANY_ALL_DETAILS);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/company', methods: Request::METHOD_GET)]
    public function adminIndex() : JsonResponse
    {
        return $this->jsonWithGroup($this->entityManager->getRepository(Company::class)->findAll(),
            ContextGroup::ADMIN_COMPANY_SEARCH);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/company/{company}', requirements: ['id' => CustomRequirement::SIGNED_INT],
        methods: Request::METHOD_GET)]
    public function adminShow(Company $company) : JsonResponse
    {
        return $this->jsonWithGroup($company, ContextGroup::ADMIN_COMPANY_SEARCH);
    }
}
