<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {

    }

    #[Route('/test', name: 'test')]
    public function test() : JsonResponse
    {
        $company = new Company();
        $company->setName("Test Company");
        $company->setAddress("Test Address");

        $user = new User();
        $user->setName("Petar");
        $user->setRole(UserRole::FrontEnd);
        $user->setCompany($company);

        $this->entityManager->persist($company);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user->getName().$company->getName());
    }
}