<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testLoginWithAdminAccount()
    {
        $data = [
          'email' => 'admin@gmail.com',
          'password' => '123456'
        ];

        $user = $this->json('POST' , '/api/auth/login' , $data);

        $user->assertStatus(200)->assertJson(['data' => ['role' => 'Admin']]);
    }

    public function testLoginValidation()
    {
        $data = [
            'email' => 'admdfdfin@gmail.com',
            'password' => '12dfdfdf3456'
        ];

        $user = $this->json('POST' , '/api/auth/login' , $data);
        $user->assertStatus(200)->assertJson(['message' => 'Unauthorized']);
    }
}
