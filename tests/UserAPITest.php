<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserAPITest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testCreateUser()
    {
        $user = ['name' => 'Bhaskar', 'email' => 'xlinkerz@gmail.com'];
        $response = $this->call('POST', '/api/v1/user', $user);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('users', ['email' => 'xlinkerz@gmail.com']);
    }
}
