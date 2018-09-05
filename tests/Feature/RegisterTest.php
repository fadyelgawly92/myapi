<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegistersSuccessfullly()
    {
        $payload = [
            'name' => 'john',
            'email' => 'john@toptal.com',
            'password' => 'toptal123',
            'password_confirmation' => 'toptal123'
        ];

        $this->json('POST','/api/register',$payload)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
                'api_token',
            ],
        ]);
    }

    public function testRequiresPasswordEmailAndName()
    {
        $this->json('POST','/api/register')
        ->assertStatus(422)
        ->assertJson([
            'name' => ['The name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.']
        ]);
    }

    public function testRequirePasswordConfirmation()
    {
        $payload = [
            'name' => 'john',
            'email' => 'john@toptal.com',
            'password' => 'toptal123',
        ];

        $this->json('POST','/api/register',$payload)
        ->assertStatus(422)
        ->assertJson([
            'password' => ['The password Confirmation does not match'],
        ]);
    }
}
