<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestController;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class LoginControllerTest extends BaseTestController
{
    private static ?KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {
        self::markTestSkipped();

        parent::setUpBeforeClass();

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(User::class);

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Create a User fixture
        /** @var UserPasswordHasher $passwordHasher */
        $passwordHasher = $container->get(UserPasswordHasher::class);

        $user = new User();
        $user->setEmail('email@example.com');
        $user->setName('test');
        $user->setRole('test');
        $user->setSurname('test');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));
        $user->setPasswordNoHash('password');
        $user->setCompany(null);

        $em->persist($user);
        $em->flush();

        self::ensureKernelShutdown();
    }

    public function testLogin(): void
    {
        // Denied - Can't login with invalid email address.
        $response = static::createClient()
            ->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $client = $this->getClient();

        $client->submitForm('sign_in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        $client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $client->submitForm('sign-in', [
            '_username' => 'email@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        $client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $client->submitForm('sign-in', [
            '_username' => 'email@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/');
        $client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }
}
