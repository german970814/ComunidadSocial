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

let users = {};
const redisClient = redis.createClient();

server.listen(3000, 'localhost', () => {
  console.log('Server started on port 3000')

  // Nos subscribimos al canal que nos dice si hay usuarios conectados
  redisClient.subscribe('user.connection');
  console.log('[Redis] Subscribed')

  redisClient.on('message', function (channel, data) {
    console.log(`[Redis][Channel]: ${channel}`)
    switch (channel) {
      case 'user.connection':
        const redis_data = JSON.parse(data);
        data = JSON.parse(redis_data)

        if (data.id in users) {
          users[data.id] = Object.assign({}, users[data.id], {
            name: data.name,
            fromRedis: true,
            friends: data.friends,
          });
        } else {
          users[data.id] = {
            online: false,
            name: data.name,
            fromRedis: true,
            friends: data.friends,
          }
        }

        console.log(`[Server]: Online users ${Object.keys(users).length}`)
        break;
      default:
        break;
    }
  })
});


io.listen(server).on('connection', function(client) {
  /**
   * Cuando un usuario se crea, se guarda en una lista de usuarios
   * junto con la conexión que este usuario genera, para luego
   * poder emitir eventos
   */
  client.on('add user', function(data) {
    if (data.id in users) {
      console.log(`[User] Connected user ${data.id}`)
      // users[data.id].connect = true;
      client._user_id = data.id;
      users[data.id] = Object.assign({}, users[data.id], {
        online: true, connection: client, fromRedis: false
      });

      users[data.id].friends && users[data.id].friends.forEach(friend => {
        if (friend in users && users[friend].online) {
          users[friend].connection.emit('connected_users', {
            id: data.id,
            name: users[data.id].name,
          });
          users[data.id].connection.emit('connected_users', {
            id: friend,
            name: users[friend].name,
          });
        }
      });
    }
  });

  /**
   * Cuando un usuario se desconecta, le emitimos un evento a cada amigo conectado
   * para que pueda re-renderizar el dom
   */
  client.on('disconnect', function(data) {
    console.log(`[User][${client._user_id}]: Disconnect with ${data}`)

    if (!client._user_id || !(client._user_id in users)) return;

    if (!users[client._user_id].fromRedis) {
      users[client._user_id].friends && users[client._user_id].friends.forEach(friend => {
        if (friend in users && users[friend].online) {
          users[friend].connection.emit('user_disconnect', client._user_id)
        }
      });
  
      users[client._user_id] = { online : false };
      console.log(`[User][${client._user_id}]: Disconnect successfull`)
    }
  });
});