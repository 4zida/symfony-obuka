<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Util\ContextGroup;
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

class UserController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/api/user/', methods: Request::METHOD_GET)]
    public function index() : JsonResponse
    {
        return $this->jsonWithGroup($this->userRepository->findAll(), ContextGroup::USER_ALL_DETAILS);
    }

    #[Route('/api/user/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function show(User $user) : JsonResponse
    {
        return $this->jsonWithGroup($user, ContextGroup::USER_ALL_DETAILS);
    }

    #[Route('/api/user/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_PATCH)]
    public function update(User $user, Request $request) : Response
    {
        $this->handleJSONForm($request, $user, UserType::class, [], false);
        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::USER_UPDATED);
    }

    #[Route('/api/user/', methods: Request::METHOD_POST)]
    public function create(Request $request) : Response
    {
        $user = new User();
        $this->handleJSONForm($request, $user, UserType::class);

        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::USER_CREATED);
    }

    #[Route('/api/user/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_DELETE)]
    public function delete(User $user) : Response
    {
        $user->setIsActive(false);
        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::USER_DELETED);
    }

    #[Route('/api/user/search/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function findById(int $id) : JsonResponse
    {
        $user = $this->userRepository->find($id);

        return $this->jsonWithGroup($user, ContextGroup::USER_ALL_DETAILS);
    }

    #[Route('/api/user/search/role/{role}', methods: Request::METHOD_GET)]
    public function findByRole(string $role) : JsonResponse
    {
        $user = $this->userRepository->getUsersByRole($role);

        return $this->jsonWithGroup($user, ContextGroup::USER_ALL_DETAILS);
    }

    #[Route('/api/user/search/company/{id}', requirements: ['id' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function findByCompany(Company $company) : JsonResponse
    {
        $user = $this->userRepository->getUsersByCompany($company);

        return $this->jsonWithGroup($user, ContextGroup::USER_ALL_DETAILS);
    }
}
