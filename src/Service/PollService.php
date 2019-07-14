<?php

namespace App\Service;

use App\DataService\DataServiceInterface;
use App\WebsocketService\WebSocketServiceInterface;

class PollService
{
    private $dataService;
    private $notifierService;

    /**
     * PollService constructor.
     * @param $dataService DataServiceInterface
     * @param $notifierService WebSocketServiceInterface
     */
    public function __construct($dataService, $notifierService)
    {
        $this->dataService = $dataService;
        $this->notifierService = $notifierService;
    }

    public function getQuestion($uid)
    {
        return $this->dataService->getQuestion($uid);
    }

    public function getAnswers($uid)
    {
        return $this->dataService->getAnswers($uid);
    }

    public function getResults($uid)
    {
        return $this->dataService->getResults($uid);
    }

    public function createPoll($question, $answers, $uid)
    {
        $this->dataService->createPoll($question, $uid);

        foreach ($answers as $answer) {
            $this->dataService->createAnswer($answer, $uid);
        }
    }

    public function saveVote($userName, $result)
    {
        $this->dataService->saveVote($userName, $result);
    }

    public function checkAnswerValid($uid, $answer)
    {
        return $this->dataService->checkAnswerIsInPoll($uid, $answer);
    }

    public function notifyVoters($uid, $answers, $results)
    {
        $this->notifierService->notify($uid, $answers, $results);
    }

    public function sendResponse($responseBody, $code)
    {
        http_response_code($code);
        echo $responseBody;
    }
}