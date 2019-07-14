<?php


namespace App\DataService;


class FixturesDataService implements DataServiceInterface
{
    private $pdo;

    private $polls = [
        [
            'uid' => 'uid1',
            'question' => 'Are you ready for XIAG?'
        ],
        [
            'uid' => 'uid2',
            'question' => 'Where we go tonight?'
        ]
    ];

    private $answers = [
        [
            'id' => 1,
            'values' => 'Yes',
            'poll_uid' => 'uid1'
        ],
        [
            'id' => 2,
            'values' => 'Of course',
            'poll_uid' => 'uid1'
        ],
        [
            'id' => 3,
            'values' => 'Absolutely',
            'poll_uid' => 'uid1'
        ],
        [
            'id' => 4,
            'values' => 'Yes',
            'poll_uid' => 'uid2'
        ],
        [
            'id' => 5,
            'values' => 'Maybe',
            'poll_uid' => 'uid2'
        ],
    ];

    private $results = [
        [
            'answer_id' => 1,
            'user_name' => 'Joe'
        ],
        [
            'answer_id' => 2,
            'user_name' => 'Peter'
        ],
        [
            'answer_id' => 5,
            'user_name' => 'Jane'
        ],
    ];

    public function getQuestion($uid)
    {
        $question = array_filter($this->polls, function ($poll) use ($uid) {
            return $poll['uid'] == $uid;
        })[0];
        return $question['question'];
    }

    public function getAnswers($uid)
    {
        return array_filter($this->answers, function ($answer) use ($uid) {
            return $answer['poll_uid'] == $uid;
        });
    }

    public function getResults($uid)
    {
        $answers = $this->getAnswers($uid);
        return array_filter($this->results, function ($result) use ($uid, $answers) {
            $results = array_filter($answers, function ($answer) use ($result) {
                return $answer['id'] == $result['answer_id'];
            });
            return sizeof($results) > 0;
        })[0];
    }

    public function createPoll($question, $uid)
    {

    }

    public function createAnswer($answer, $uid)
    {

    }

    public function saveVote($userName, $answer)
    {

    }

    public function checkAnswerIsInPoll($uid, $answer_id)
    {
        return sizeof(array_filter($this->answers, function ($answer) use ($uid, $answer_id) {
                return $answer['poll_uid'] == $uid && $answer['id'] == $answer_id;
            })) > 0;
    }

}