<?php


namespace App\WebsocketService;


interface WebSocketServiceInterface
{
    public function notify($uid, $answers, $results);
}