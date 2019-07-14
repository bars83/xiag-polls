const express = require('express');
const app = express();
const http = require('http').Server(app);
const io = require('socket.io')(http);

// подключаемся к redis
const subscriber = require('redis').createClient({
  host: 'redis',
  port: 6379,
  password: 'xiag'
});

// подписываемся на изменения в каналах redis
subscriber.on('message', function(channel, message) {
  // // пересылаем сообщение из канала redis в комнату socket.io
  // io.emit('xiagRoom', message);
  console.log(channel);
  console.log(message);
  io.sockets.in(channel).emit('message', message);
});

// открываем соединение socket.io
io.on('connection', function(socket){
  socket.on('room', function(room) {
    // подписываемся на канал redis 'xiag' в callback
    console.log(room);
    subscriber.subscribe(room);
    socket.join(room);
  });
});

const port = process.env.PORT || 5000;

http.listen(
  port,
  function() {
    console.log('Listen at ' + port);
  }
);
