<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class LoginControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    protected static ?KernelBrowser $client;
    protected static ?KernelInterface $kernel;
    protected static ?User $user;
    protected static ?EntityManagerInterface $em;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$kernel = self::createKernel();
        self::bootKernel();
        $container = self::$kernel->getContainer();

        self::$em = self::getEntityManager();

        // Remove any existing users from the test database
        foreach (self::$em->getRepository(User::class)->findAll() as $user) {
            self::removeEntity($user);
        }

        // Create a User fixture
        /** @var UserPasswordHasher $passwordHasher */
        $passwordHasher = $container->get(UserPasswordHasher::class);

        self::$user = (new User());
        self::$user
            ->setEmail('email@example.com')
            ->setName('test')
            ->setRole(UserRole::BackEnd)
            ->setSurname('test')
            ->setRoles(['ROLE_USER'])
            ->setPassword($passwordHasher->hashPassword(self::$user, 'password'))
            ->setPasswordNoHash('password')
            ->setCompany(null);

        self::persistEntity(self::$user);

        self::ensureKernelShutdown();
    }

    public function testLogin(): void
    {
        self::$client = self::createClient();

        // Denied - Can't login with invalid email address.
        self::$client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        self::$client->submitForm("login", [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        self::$client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        self::$client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        self::$client->submitForm('login', [
            '_username' => 'email@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        self::$client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        self::$client->submitForm('login', [
            '_username' => 'email@example.com',
            '_password' => 'password',
        ]);

//        self::assertResponseRedirects('/');
//        self::$client->followRedirect();
//
//        self::assertSelectorNotExists('.alert-danger');
//        self::assertResponseIsSuccessful();
    }

    public static function tearDownAfterClass(): void
    {
        self::removeEntity(self::$user);

        parent::tearDownAfterClass();
    }
}
