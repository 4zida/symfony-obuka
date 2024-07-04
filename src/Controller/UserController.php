<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Util\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user', name: 'user_api')]
class UserController extends BaseRestController
{
    use FormTrait;

    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Rest\Get('/', name: 'index', methods: Request::METHOD_GET)]
    public function index() : Response
    {
        $data = $this->serializeJSON($this->userRepository->findAll(), $this->serializer, context: SerializerHelper::USER_CONFIG);

        return $this->generateOkResponse($data);
    }

    #[Rest\Get('/{id}', name: 'show', methods: Request::METHOD_GET)]
    public function show(User $user) : Response
    {
        $data = $this->serializeJSON($user, $this->serializer, context:  SerializerHelper::USER_CONFIG);

        return $this->generateOkResponse($data);
    }

    #[Rest\Patch('/{id}', name: 'update', methods: Request::METHOD_PATCH)]
    public function update(User $user, Request $request) : Response
    {
        $this->handleJSONForm($request, $user, UserType::class, [], false);
        $this->entityManager->flush();

        return $this->generateOkResponse('User updated.');
    }

    #[Rest\Post('/', name: 'create', methods: Request::METHOD_POST)]
    public function create(Request $request, SerializerInterface $serializer) : Response
    {
        $user = new User();
        $this->handleJSONForm($request, $user, UserType::class);

        $this->entityManager->flush();

        return $this->generateOkResponse('User created.');
    }

    #[Rest\Delete('/{id}', name: 'delete', methods: Request::METHOD_DELETE)]
    public function delete(User $user) : Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->generateOkResponse('User deleted.');
    }

    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: Request::METHOD_GET)]
    public function findById(int $id) : Response
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            return $this->generateNotFoundResponse('Users not found.');
        }

        $data = $this->serializeJSON($user, $this->serializer, context:  SerializerHelper::USER_CONFIG);

        return $this->generateOkResponse($data);
    }

    #[Rest\Get('/search/role/{role}', name: 'search_by_role', methods: Request::METHOD_GET)]
    public function findByRole(string $role) : Response
    {
        $user = $this->userRepository->findBy(['role' => $role]);

        if (!$user) {
            return $this->generateNotFoundResponse('Users not found.');
        }

        $data = $this->serializeJSON($user, $this->serializer, context:  SerializerHelper::USER_CONFIG);

        return $this->generateOkResponse($data);
    }

    #[Rest\Get('/search/company/{company}', name: 'search_by_company', methods: Request::METHOD_GET)]
    public function findByCompany(Company $company) : Response
    {
        $user = $this->userRepository->findBy(['company' => $company]);

        if (!$user) {
            return $this->generateNotFoundResponse('Users not found.');
        }

        $data = $this->serializeJSON($user, $this->serializer, context:  SerializerHelper::USER_CONFIG);

        return $this->generateOkResponse($data);
    }
}
