<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class AuthenticationTest extends ApiTestCase
{

    public function testLogin(): void
    {
        $client = self::createClient(['test@hotmail.fr', 'changeMe']);

        $response = $client->request('POST', '/authentication_token', [
            'auth_basic' => ['test@hotmail.fr', 'changeMe'],
        ]);

        // Verifie que l'api renvoie bien un token
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $response);

        // Test 
        $client->request('GET', '/api/users/3');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/users/3', ['auth_bearer' => $response['token']]);
        $this->assertResponseIsSuccessful();
    }
}
