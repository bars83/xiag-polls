<?php


namespace App\WebsocketService;


class RedisChatNotifierService implements WebSocketServiceInterface
{
public function notify($uid, $answers, $results)
{
    $redis = new \Redis();
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
}