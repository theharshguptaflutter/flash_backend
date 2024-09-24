var express = require('express');
var http = require('http');
var app = express();
var server = http.createServer(app);


var io = require('socket.io').listen(server, { path: '/chat/socket.io' });


app.get('/chat', function(req, res) {
   res.statusCode = 200;
      res.setHeader('Content-Type', 'text/plain');
      res.end('Hello World!\n');
});

app.get('/', function(req, res) {
   res.statusCode = 200;
      res.setHeader('Content-Type', 'text/plain');
      res.end('Hello World 1!\n');
});
io.on('connection', function(socket) {
   console.log('A user connected');

});

server.listen(4002, function() {
   console.log('listening on localhost:4002');
});
