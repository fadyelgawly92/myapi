<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserIsLoggedOutProperly()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET','/api/articles',[],$headers)->assertStatus(200);
        $this->json('POST','/api/logout',[],$headers)->assertStatus(200);

        $user = User::find($user->id);

        $this->assertEquals(null,$user->api_token);
    }

    public function testUserWithNullToken()
    {
        //simulating login
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        //simulating logout
        $user->api_token = null;
        $user->save();

        $this->json('GET','/api/articles',[],$headers)->assertStatus(401);
    }
}
