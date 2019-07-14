<?php

namespace App;

use App\Controller\PollController;
use App\DataService\FixturesDataService;
use App\Service\PollService;
use App\WebsocketService\StubChatNotifierService;
use PHPUnit\Framework\TestCase;

final class PollTest extends TestCase
{
    private $controller;
    private $service;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->controller = new PollController(new FixturesDataService(), new StubChatNotifierService());
        $this->service = new PollService(new FixturesDataService(), new StubChatNotifierService());
    }

    public function testControllerCreated(): void
    {
        $this->assertInstanceOf(PollController::class, $this->controller);
    }

    public function testGetQuestion(): void
    {
        $this->assertEquals('Are you ready for XIAG?', $this->service->getQuestion('uid1'));
    }

    public function testIndexActionSuccessResponse(): void
    {
        $this->controller->showPollAction('uid1', 0);
        $this->assertEquals(200, http_response_code());
    }

    public function testCannotVoteWithInvalidAnswerIdByResponseCode(): void
    {
        $this->controller->addVote('uid1', 'username', 44);
        $this->assertEquals(403, http_response_code());
    }

    public function testCannotVoteWithAnswerFromOtherPollByResponseCode(): void
    {
        $this->controller->addVote('uid1', 'username', 5);
        $this->assertEquals(403, http_response_code());
    }

    public function testCanVoteByResponse(): void
    {
        $this->controller->addVote('uid1', 'username', 1);
        $this->assertEquals(200, http_response_code());
    }

    public function testCanVote(): void
    {
        $this->assertEquals(true, $this->service->checkAnswerValid('uid1', 1));
    }

    public function testCanNotVoteWithAnswerFromOtherPoll(): void
    {
        $this->assertEquals(false, $this->service->checkAnswerValid('uid1', 5));
    }
}