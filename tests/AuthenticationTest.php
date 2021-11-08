<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class AuthenticationTest extends ApiTestCase
{

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail('random@yahoo.fr');
        $user->setPassword(
            self::getContainer()->get('security.user_password_hasher')->hashPassword($user, 'changeMe')
        );

        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'random@yahoo.fr',
                'password' => 'changeMe',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/activities/1');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/activities/1', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}
