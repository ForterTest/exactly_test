<?php

namespace Tests\Unit;

use App\Exceptions\IncorrectAmountException;
use App\Exceptions\UserIdNotFoundException;
use App\Http\Controllers\OperationsController;
use Domain\Services\OperationsService;
use Domain\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OperationDataValidationTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testValidData()
    {
        $this->assertTrue($this->checkOperationData($this->user->id,100));
    }

    public function testBadUser1()
    {
        $this->expectException(UserIdNotFoundException::class);
        $this->checkOperationData("asd",100);
    }

    public function testBadUser2()
    {
        $this->expectException(UserIdNotFoundException::class);
        $this->checkOperationData(2,100);
    }

    public function testBadAmount1()
    {
        $this->expectException(IncorrectAmountException::class);
        $this->checkOperationData($this->user->id, 0);
    }

    public function testBadAmount2()
    {
        $this->expectException(IncorrectAmountException::class);
        $this->checkOperationData($this->user->id,-100);
    }

    public function testBadAmount3()
    {
        $this->expectException(IncorrectAmountException::class);
        $this->checkOperationData($this->user->id, "asd");
    }

    private function checkOperationData($userId, $amount)
    {
        $operationsService = new OperationsService();
        $operationsController = new OperationsController($operationsService);

        return $this->invokePrivateMethod($operationsController, 'validateOperationData', [$userId, $amount]);
    }
}
