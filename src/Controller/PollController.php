<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/../Service/PollService.php';

class PollController
{
    /**
     * @var $service PollService
     */
    private $service;

    /**
     * @var $twig Environment
     */
    private $twig;

    public function __construct()
    {
        $this->service = new PollService();
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    public function indexAction()
    {
        $body = $this->twig->render('new.html.twig');
        $this->service->sendResponse($body, 200);
    }

    public function showPollAction($uid, $voted)
    {

        $question = $this->service->getQuestion($uid);

        $answers = $this->service->getAnswers($uid);

        $results = $this->service->getResults($uid);

        try {
            $body = $this->twig->render(
                'poll.html.twig',
                [
                    'uid' => $uid,
                    'question' => $question,
                    'answers' => $answers,
                    'results' => $results,
                    'voted' => $voted]
            );
            $this->service->sendResponse($body, 200);
        } catch (Exception $e) {
            $this->service->sendResponse('error 500', 500);
        }

    }

    public function createPollAction($question, $answers)
    {

        $uid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        try {
            $this->service->createPoll($question, $answers, $uid);
            header('Location: /' . $uid);
        } catch (Exception $e) {
            $this->service->sendResponse('error 500', 500);
        }
    }

    public function addVote($uid, $userName, $result)
    {
        // uid can be finded by answer id ($result) so it is possible to post just answer, but somebody could try
        // to bruteforce out poll service by posting answers and getting other users poll uid in response
        $check = $this->service->checkAnswerValid($uid, $result);
        if (!$check) {
            $this->service->sendResponse('Forbidden', 403);
            return;
        }

        $this->service->saveVote($userName, $result);

        $answers = $this->service->getAnswers($uid);
        $results = $this->service->getResults($uid);

        $this->service->notifyVoters($uid, $answers, $results);

        $this->service->sendResponse('', 200);
    }
}