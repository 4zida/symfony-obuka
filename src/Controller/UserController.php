<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user', name: 'user_api')]
class UserController extends AbstractFOSRestController
{
    public array $userSerializerConfig = [
        AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
        AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
        'groups' => [
            'list_company_no_users',
            'list_user_all'
        ]
    ];

    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Rest\Get('/', name: 'index', methods: ['GET'])]
    public function index() : Response
    {
        $data = $this->serializer->serialize($this->userRepository->findAll(), 'json', $this->userSerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user) : Response
    {
        $data = $this->serializer->serialize($user, 'json', $this->userSerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Put('/{id}', name: 'update', methods: ['PUT'])]
    public function update(User $user, Request $request) : Response
    {
        $requestContent = $request->getContent();
        $updatedUser = $this->serializer->deserialize($requestContent, User::class, 'json');

        $user->setName($updatedUser->getName());
        $user->setSurname($updatedUser->getSurname());
        $user->setRole($updatedUser->getRole());
        $user->setEmail($updatedUser->getEmail());
        $user->setPassword($updatedUser->getPassword());

        $this->entityManager->flush();

        return new Response('User updated.', Response::HTTP_OK);
    }

    #[Rest\Post('/', name: 'create', methods: ['POST'])]
    public function create(Request $request) : Response    // TODO
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->entityManager;
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            return new Response('User created.', Response::HTTP_CREATED);
        }
        return new Response('User not created.', Response::HTTP_BAD_REQUEST);
    }

    #[Rest\Delete('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user) : Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response('User deleted.', Response::HTTP_OK);
    }

    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: ['GET'])]
    public function findById(int $id) : Response
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            return new Response('Users not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($user, 'json', $this->userSerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/search/role/{role}', name: 'search_by_role', methods: ['GET'])]
    public function findByRole(string $role) : Response
    {
        $user = $this->userRepository->findBy(['role' => $role]);

        if (!$user) {
            return new Response('Users not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($user, 'json', $this->userSerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/search/company/{company}', name: 'search_by_company', methods: ['GET'])]
    public function findByCompany(Company $company) : Response
    {
        $user = $this->userRepository->findBy(['company' => $company]);

        if (!$user) {
            return new Response('Users not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($user, 'json', $this->userSerializerConfig);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
