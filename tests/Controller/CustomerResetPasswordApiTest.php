<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller;

use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Sylius\ShopApiPlugin\Controller\Utils\PurgeSpooledMessagesTrait;

final class CustomerResetPasswordApiTest extends JsonApiTestCase
{
    use PurgeSpooledMessagesTrait;

    /**
     * @test
     */
    public function it_allows_to_verify_customer()
    {
        $this->loadFixturesFromFiles(['customer.yml']);

        $data = '{"email": "oliver@queen.com"}';

        $this->client->request('PUT', '/shop-api/request-password-reset', [], [], ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'], $data);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->get('sylius.repository.shop_user');
        /** @var ShopUserInterface $user */
        $user = $userRepository->findOneByEmail('oliver@queen.com');

        $newPasswords =
<<<EOT
        {
            "password" : {
                "first": "somepass",
                "second": "somepass"
            }
        }
EOT;

        $this->client->request('PUT', '/shop-api/password-reset/' . $user->getPasswordResetToken(), [], [], ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'], $newPasswords);

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    protected function getContainer(): ContainerInterface
    {
        return static::$sharedKernel->getContainer();
    }
}
