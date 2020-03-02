<?php

namespace Tests\Unit;

use Domain\Models\User;
use Domain\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserRepository()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $userRepository = new UserRepository();

        $user = $userRepository->getById(1);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->id, 1);

        $users = $userRepository->getAllExceptId(1);
        $this->assertInstanceOf(Collection::class, $users);
        $this->assertCount(2, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertInstanceOf(User::class, $users[1]);
        $this->assertEquals($users[0]->id, 2);
        $this->assertEquals($users[1]->id, 3);
    }
}
