<?php

namespace App;

use App\Controller\PollController;
use App\DataService\MySqlDataService;
use App\WebsocketService\RedisChatNotifierService;

require_once __DIR__ . '/../vendor/autoload.php';


$controller = new PollController(new MySqlDataService(), new RedisChatNotifierService());


$router = new \Bramus\Router\Router();

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

$router->get('/((\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8})', function ($uid) use ($controller) {
    /**
     * TODO: inspect this workaround (bug with uid cutting last two symbols)
     */
    $uid = substr($_SERVER['REQUEST_URI'], 1);
    $voted = $_COOKIE['xiag_poll_' . $uid] == '1';
    $controller->showPollAction($uid, $voted);
    exit;
});

$router->post('/new', function () use ($controller) {

    $question = $_POST['question'];
    $answers = [];
    foreach ($_POST['answer'] as $answer) {
        $answers[] = $answer;
    }
    $controller->createPollAction($question, $answers);
    exit;

});

$router->post('/vote', function () use ($controller) {
    $uid = $_POST['uid'];
    $userName = $_POST['user_name'];
    $result = $_POST['result'];
    $controller->addVote($uid, $userName, $result);
    exit;
});

$router->get('/', function () use ($controller) {
    $controller->indexAction();
    exit;
});


$router->run();
