<?php

namespace App\DataService;

interface DataServiceInterface
{

public function getQuestion($uid);

public function getAnswers($uid);

public function getResults($uid);

public function createPoll($question, $uid);

public function createAnswer($answer, $uid);

public function saveVote($userName, $answer);

public function checkAnswerIsInPoll($uid, $answer);



}