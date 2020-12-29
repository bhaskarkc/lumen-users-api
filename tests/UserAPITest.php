<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserAPITest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectsEvents('App\Events\UserEvent');
    }

    public function testCreateUser()
    {
        $user = ['name' => 'Bhaskar', 'email' => 'xlinkerz@gmail.com'];
        $response = $this->call('POST', '/api/v1/user', $user);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('users', ['email' => 'xlinkerz@gmail.com']);
    }


    public function testViewUser()
    {
        $response = $this->call('GET', '/api/v1/user/1');
        $this->assertEquals(200, $response->status());
    }

    public function testUpdateUser()
    {
        $updated_user = ['name' => 'Bhaskar', 'email' => 'updated@gmail.com'];
        $response = $this->call('PUT', '/api/v1/user/1', $updated_user);
        $this->assertEquals(200, $response->status());
    }


    public function testDeleteUser()
    {
        $response = $this->call('DELETE', '/api/v1/user/1');
        $this->seeJsonEquals(["message" => "User deleted."]);
        $this->assertEquals(200, $response->status());
    }
}
