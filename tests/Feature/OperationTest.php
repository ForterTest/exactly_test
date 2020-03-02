<?php

namespace Tests\Feature;

use Domain\Models\User;
use Domain\Repositories\UserRepository;
use Domain\Services\HistoryService;
use Domain\Services\OperationsService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Facades\Domain\Types\Amount as AmountType;

class OperationTest extends TestCase
{
    use DatabaseMigrations;

    private $user1;
    private $user2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = factory(User::class)->create(['balance' => 5500]);
        $this->user2 = factory(User::class)->create();
    }

    public function testOperationTest()
    {
        // Operation
        $operationsService = new OperationsService();

        $operationsService->sendTo($this->user1, 2, AmountType::fromMinor(12.55));

        $userRepository = new UserRepository();
        $user1 = $userRepository->getById(1);
        $user2 = $userRepository->getById(2);

        $this->assertEquals($user1->balance_in_minor_units, 42.45);
        $this->assertEquals($user2->balance_in_minor_units, 12.55);

        $this->checkHistory();
    }

    public function checkHistory()
    {
        // History of operations
        $historyService = new HistoryService();

        $history1 = $historyService->getHistoryForUser($this->user1);
        $this->assertCount(1, $history1);
        $this->assertEquals($history1[0]['amount'], -12.55);
        $this->assertEquals($history1[0]['new_balance'], 42.45);

        $history2 = $historyService->getHistoryForUser($this->user2);
        $this->assertCount(1, $history2);
        $this->assertEquals($history2[0]['amount'], 12.55);
        $this->assertEquals($history2[0]['new_balance'], 12.55);
    }
}
