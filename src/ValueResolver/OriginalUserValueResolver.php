<?php

namespace App\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class OriginalUserValueResolver implements ValueResolverInterface
{
    private TokenStorageInterface $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array<int, UserInterface|null>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if(UserInterface::class !== $argument->getType() &&
            !$argument->getAttributesOfType(OriginalUser::class, ArgumentMetadata::IS_INSTANCEOF))
        {
            return [];
        }

        $token = $this->tokenStorage->getToken();

        if ($token instanceof SwitchUserToken) {
            $user = $token->getOriginalToken()->getUser();
        } else {
            $user = $token->getUser();
        }

        if ($user === null) {
            if ($argument->hasDefaultValue()) {
                return [$argument->getDefaultValue()];
            }
            if (!$argument->isNullable()){
                throw new AccessDeniedException('There is no logged in user.');
            }

            return [null];
        }

        if ($argument->getType() === null || $user instanceof ($argument->getType())) {
            return [$user];
        }

        throw new AccessDeniedException(
            sprintf(
                'The logged in user is an instance of %s, but an instance of %s was expected',
                $user::class,
                $argument->getType()
            )
        );
    }
}