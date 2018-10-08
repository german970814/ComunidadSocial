/**
 * 
 * Node WebSocket server
 */

'use strict';

const express = require('express');
const http = require('http');
const app = express();

const server = http.createServer(app);
 
 
const redis = require('redis');
const io = require('socket.io');
const client = redis.createClient();

let users = [];

server.listen(3000, 'localhost', () => {
  console.log('Server started on port 3000')
});


io.listen(server).on('connection', function(client) {
  const redisClient = redis.createClient();

  /**
   * Cuando un usuario se crea, se guarda en una lista de usuarios
   * junto con la conexión que este usuario genera, para luego
   * poder emitir eventos
   */
  client.on('add user', function(data) {
    if(!(data.usuario_id in users)) {
      users[data.usuario_id] = {
        'name': data.full_name,
        'conn': { client }
      }
      client.usuario_id = data.usuario_id
    }
  });
 
  // Nos subscribimos al canal que nos dice si hay usuarios conectados
  redisClient.subscribe('user.connection');

  redisClient.on('message', function (channel, data) {
    switch (channel) {
      case 'user.connection':
        const redis_data = JSON.parse(data);
        redis_data.forEach(user => {
          let user_data = JSON.parse(user);
          if (user_data.usuario_id in users) {  // si el usuario está conectado
            users[user_data.usuario_id].conn.client.friends = user_data.friends; // agregamos los amigos al socket
  
            for (let user of user_data.friends) {
              if (user in users) {
                // Para cada amigo, emitimos un evento para que sepan que estamos online
                users[user].conn.client.emit('connected_users', {
                  'name': user_data.full_name,
                  'usuario_id': user_data.usuario_id
                })
              }
            }
          }
        });
        break;
      default:
        break;
    }
  })

  /**
   * Cuando un usuario se desconecta, le emitimos un evento a cada amigo conectado
   * para que pueda re-renderizar el dom
   */
  client.on('disconnect', function(data) {
      if (!(client.usuario_id in users)) return;

      client.friends.forEach(f => {
        (f in users) && users[f].conn.client.emit('user_disconect', client.usuario_id);
      })

      delete users[client.usuario_id];

      redisClient.quit();
  });
});