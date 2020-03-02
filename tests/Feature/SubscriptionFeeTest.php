<?php

namespace Tests\Feature;

use Domain\Models\User;
use Domain\Repositories\UserRepository;
use Domain\Services\HistoryService;
use Domain\Services\OperationsService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Facades\Domain\Types\Amount as AmountType;

class SubscriptionFeeTest extends TestCase
{
    use DatabaseMigrations;

    private $user1;
    private $user2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = factory(User::class)->create(['balance' => 5500]);
        $this->user2 = factory(User::class)->create(['balance' => 3200]);
    }

    public function testSubscriptionFee()
    {
        $subscriptionService = app('SubscriptionService');

        $subscriptionService->chargeFees();

        $userRepository = new UserRepository();
        $user1 = $userRepository->getById(1);
        $user2 = $userRepository->getById(2);

        $this->assertEquals($user1->balance_in_minor_units, 54.00);
        $this->assertEquals($user2->balance_in_minor_units, 31.00);
    }

}
