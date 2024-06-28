<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Ad;
use App\Entity\Company;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/ad', name: 'ad_api')]
class AdController extends AbstractFOSRestController
{
    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Rest\Get('/', name: 'index', methods: ['GET'])]
    public function index() : Response
    {
        $data = $this->serializer->serialize($this->documentManager->getRepository(Ad::class)->findAll(), 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/{id}', name: 'show', methods: ['GET'])]
    public function show(Ad $ad) : Response
    {
        $data = $this->serializer->serialize($ad, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Put('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Ad $ad, Request $request) : Response
    {
        $requestContent = $request->getContent();
        $updatedAd = $this->serializer->deserialize($requestContent, Ad::class, 'json');

        $ad->setName($updatedAd->getName());
        $ad->setUrl($updatedAd->getUrl());
        $ad->setDescription($updatedAd->getDescription());
        $ad->setCompanyId($updatedAd->getCompanyId());
        $ad->setUserId($updatedAd->getUserId());

        $this->documentManager->persist($ad);
        $this->documentManager->flush();

        return new Response('Ad updated.', Response::HTTP_OK);
    }

    #[Rest\Post('/', name: 'create', methods: ['POST'])]
    public function create(Request $request) : Response
    {
//        $ad = new Ad();
//        $form = $this->createForm(AdType::class, $ad);
//
//        $data = $this->serializer->deserialize($request->getContent(), Ad::class, 'json');
//        $form->submit($data);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $dm = $this->documentManager;
//            $dm->persist($ad);
//            $dm->flush();
//            return new Response('Ad created.', Response::HTTP_CREATED);
//        }
//// return new Response('Ad not created.', Response::HTTP_BAD_REQUEST);
        $data = $request->getContent();
        $ad = $this->serializer->deserialize($data, Ad::class, 'json');

        if(!$ad || !$ad->getDescription() || !$ad->getName()){
            return new Response('Ad not created.', Response::HTTP_BAD_REQUEST);
        }

        $this->documentManager->persist($ad);
        $this->documentManager->flush();

        return new Response('Ad created.', Response::HTTP_CREATED);
    }

    #[Rest\Delete('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Ad $ad) : Response
    {
        $this->documentManager->remove($ad);
        $this->documentManager->flush();

        return new Response('Ad deleted.', Response::HTTP_OK);
    }

    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: ['GET'])]
    public function findById(int $id) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->find($id);

        if (null === $ad) {
            return new Response('Ads not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($ad, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/search/user/{user}', name: 'search_by_role', methods: ['GET'])]
    public function findByUser(string $user) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->findBy(['userId' => $user]);

        if (!$ad) {
            return new Response('Ads not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($ad, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/search/company/{company}', name: 'search_by_company', methods: ['GET'])]
    public function findByCompany(Company $company) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->findBy(['companyId' => $company]);

        if (!$ad) {
            return new Response('Ads not found.', Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($ad, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
