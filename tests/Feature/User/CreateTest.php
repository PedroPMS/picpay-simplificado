<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCreateANewUserAndWallet()
    {
        $user = User::factory()->make()->toArray();
        $response = $this->postJson('/users', $user);
        $userId = $response->json()['id'];

        $this->assertDatabaseHas('users', ['id' => $userId]);
        $this->assertDatabaseHas('wallet', ['user_id' => $userId]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
