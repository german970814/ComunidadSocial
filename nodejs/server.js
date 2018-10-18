/**
 * 
 * Node WebSocket server
 */

'use strict';
// TODO: cambiar la validacion en el controlador si el usuario está conectado
// TODO: Consultar usuarios conectados directamente en redis

const express = require('express');
const http = require('http');
const app = express();

const server = http.createServer(app);

const redis = require('redis');
const io = require('socket.io');
const clientPub = redis.createClient();

let users = {};
const redisClient = redis.createClient();

server.listen(3000, 'localhost', () => {
  console.log('Server started on port 3000')

  // Nos subscribimos al canal que nos dice si hay usuarios conectados
  redisClient.subscribe('user.connection');
  console.log('[Redis] Subscribed to "user.connection"');
  // Nos subscribimos al canal que nos dice si hay mensajes nuevos
  redisClient.subscribe('user.message');
  console.log('[Redis] Subscribed to "user.message"');

  redisClient.on('message', function (channel, data) {
    console.log(`[Redis][Channel]: ${channel}`)
    const redis_data = JSON.parse(data);

    switch (channel) {
      case 'user.connection':  // cuando se conecta un usuario
        // let redis_data = JSON.parse(data);
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
      case 'user.message':  // cuando le envían un mensaje al usuario
        if (redis_data.user) {
          let user = redis_data.user;
          let message = redis_data.message;
          if (user in users && users[user].online) {
            users[user].connection.emit('user_message', message);
          }
        }
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
   * Cuando el cliente hace ping al servidor, debe verificar
   * que siga activo en la base de datos de redis, en caso
   * de no estar, lo vuelve a activar
   */
  client.on('verify_conection', function (data) {
    clientPub.get(`user${client._user_id}`, (err, reply) => {
      if (!reply) {
        let dataToRedis = {
          id: client._user_id,
          name: users[client._user_id].name,
          friends: users[client._user_id].friends,
        }
        clientPub.set(`user${client._user_id}`, JSON.stringify(dataToRedis), 'EX', 60 * 10);
      }
    });
  });

  /**
   * Cuando un usuario se desconecta, le emitimos un evento a cada amigo conectado
   * para que pueda re-renderizar el dom
   */
  client.on('disconnect', function(data) {
    console.log(`[User][${client._user_id}]: Disconnect with ${data}`)

    if (!client._user_id || !(client._user_id in users)) return;

    if (data != 'ping timeout') {
      if (!users[client._user_id].fromRedis) {
        users[client._user_id].friends && users[client._user_id].friends.forEach(friend => {
          if (friend in users && users[friend].online) {
            users[friend].connection.emit('user_disconnect', client._user_id)
          }
        });
    
        users[client._user_id] = { online : false };
        console.log(`[User][${client._user_id}]: Disconnect successfull`)
      }
    }
  });
});