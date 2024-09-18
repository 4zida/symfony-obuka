<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\Phone;
use App\Entity\User;
use App\Form\PhoneType;
use App\Repository\PhoneRepository;
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

class PhoneController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly PhoneRepository        $phoneRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/api/phone/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        return $this->jsonWithGroup($this->phoneRepository->findAll(), ContextGroup::PHONE_DETAILS);
    }

    #[Route('/api/phone/{id}', methods: Request::METHOD_PATCH)]
    public function update(Phone $phone, Request $request): Response
    {
        $this->handleJSONForm($request, $phone, PhoneType::class, [], false);

        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::PHONE_UPDATED);
    }

    #[Route('/api/phone/', methods: Request::METHOD_POST)]
    public function create(Request $request): Response
    {
        $this->handleJSONForm($request, new Phone(), PhoneType::class);

        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::PHONE_CREATED);
    }

    #[Route('/api/phone/{id}', methods: Request::METHOD_DELETE)]
    public function delete(Phone $phone): Response
    {
        $this->entityManager->remove($phone);
        $this->entityManager->flush();

        return $this->createOkResponse(ResponseMessage::PHONE_DELETED);
    }

    #[Route('/api/phone/search/{id}', methods: Request::METHOD_GET)]
    public function findById(string $id): JsonResponse
    {
        $phone = $this->phoneRepository->find($id);

        return $this->jsonWithGroup($phone, ContextGroup::PHONE_DETAILS);
    }

    #[Route('/api/phone/search/user/{user}', requirements: ['user' => Requirement::POSITIVE_INT],
        methods: Request::METHOD_GET)]
    public function findByUser(User $user): JsonResponse
    {
        $phones = $this->phoneRepository->findByUser($user);

        return $this->jsonWithGroup($phones, ContextGroup::PHONE_DETAILS);
    }

}
