<?php

class PollService
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=db;charset=utf8;dbname=' . $_ENV['ENDPOINT_NAME'],
            $_ENV['ENDPOINT_NAME'],
            $_ENV['ENDPOINT_NAME']
        );
    }


    public function getQuestion($uid)
    {
        $statement = $this->pdo->prepare('select question from poll where uid=:uid');
        $statement->execute(array('uid' => $uid));
        $question = $statement->fetchColumn();
        return $question;
    }

    public function getAnswers($uid)
    {
        $statement = $this->pdo->prepare('select id, value from answer where poll_uid=:poll_uid');
        $statement->bindParam(":poll_uid", $uid, PDO::PARAM_STR);
        $statement->execute();
        $answers = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $answers;
    }

    public function getResults($uid)
    {
        $statement = $this->pdo->prepare('select r.answer_id, r.user_name from result r inner join answer a on a.id=r.answer_id where a.poll_uid=:poll_uid');
        $statement->bindParam(":poll_uid", $uid, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function createPoll($question, $answers, $uid)
    {
        $statement = $this->pdo->prepare('insert into poll(question, uid) values(:question,:uid)');
        $statement->execute(
            [
                'question' => $question,
                'uid' => $uid
            ]
        );

        foreach ($answers as $answer) {
            $statement = $this->pdo->prepare('insert into answer(poll_uid, value) values(:uid, :answer)');
            $statement->execute(
                [
                    'uid' => $uid,
                    'answer' => $answer
                ]
            );
        }
    }

    public function saveVote($userName, $result)
    {
        $statement = $this->pdo->prepare('insert into result(answer_id, user_name) values(:answer_id,:user_name)');
        $statement->execute(
            [
                'answer_id' => $result,
                'user_name' => $userName
            ]
        );
    }

    public function checkAnswerValid($uid, $answer)
    {
        $statement = $this->pdo->prepare('select count(*) from answer where poll_uid=:uid and id=:id');
        $statement->execute(
            [
                'uid' => $uid,
                'id' => $answer
            ]
        );
        $check = $statement->fetchColumn();
        return $check;
    }

    public function notifyVoters($uid, $answers, $results)
    {
        $redis = new Redis();
        $redis->connect(
            'redis',
            6379
        );
        $redis->auth('xiag');
        $room = $uid;
        $redis->publish(
            $room,
            json_encode([
                'answers' => $answers, 'results' => $results
            ])
        );
        $redis->close();
    }

    public function sendResponse($responseBody, $code)
    {
        http_response_code($code);
        echo $responseBody;
    }
}