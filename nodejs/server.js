/**
 * 
 * Node WebSocket server
 */

'use strict';
// TODO: cambiar la validacion en el controlador si el usuario está conectado

const express = require('express');
const http = require('http');
const app = express();
require('dotenv').config();

const server = http.createServer(app);

const redis = require('redis');
const io = require('socket.io')();
const clientPub = redis.createClient();

// let users = {};
const redisClient = redis.createClient();

const EXPIRE_REDIS_SESSION = 60 * 1  // 1 minute

server.listen(process.env.SOCKET_SERVER_PORT, 'localhost', () => {
  console.log(`Server started on port ${process.env.SOCKET_SERVER_PORT}`);

  // Nos subscribimos al canal que nos dice si hay usuarios conectados
  redisClient.subscribe('user.connection');
  console.log('[Redis] Subscribed to "user.connection"');
  // Nos subscribimos al canal que nos dice si hay mensajes nuevos
  redisClient.subscribe('user.message');
  console.log('[Redis] Subscribed to "user.message"');

  redisClient.on('message', function (channel, data) {
    console.log(`[Redis][Channel]: ${channel}`)
    const redisData = JSON.parse(data);

    switch (channel) {
      case 'user.connection':  // cuando se conecta un usuario
        // console.log(`[Server]: Online users ${Object.keys(users).length}`)
        break;
      case 'user.message':  // cuando le envían un mensaje al usuario
        clientPub.get(`user${redisData.user}`, (err, reply) => {
          if (reply) {
            let receptorData = JSON.parse(reply);
            if (receptorData.socketId && receptorData.socketId in io.sockets.connected) {
              io.sockets.connected[receptorData.socketId].emit('user_message', {
                ...redisData.message, name: redisData.emisor
              });
              console.log(`[Server] Message sent from #${redisData.message.usuario_id} to #${redisData.user}`);
            }
          }
        });
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
    // const dataToRedis = JSON.stringify(data);
    client._userId = data.id;
    clientPub.get(`user${client._userId}`, (err, reply) => {
      if (!reply) {
        console.log(`[Redis] Usuario #${client._userId} no ha sido encontrado`);
      } else {
        let userData = JSON.parse(reply);
        userData.socketId = client.id;

        client._userId && clientPub.set(
          `user${client._userId}`,
          JSON.stringify(userData),
          'EX', EXPIRE_REDIS_SESSION
        );
        console.log(`[User] Connected user ${data.id}`);

        userData.friends && userData.friends.forEach(friend => {
          clientPub.get(`user${friend}`, (err, replyFriend) => {
            if (replyFriend) {
              const friendData = JSON.parse(replyFriend);
              if (friendData.socketId && friendData.socketId in io.sockets.connected) {
                io.sockets.connected[friendData.socketId].emit('connected_users', {
                  id: userData.id,
                  name: userData.name
                });
                client.emit('connected_users', {
                  id: friendData.id,
                  name: friendData.name
                });
              } else if (friendData.socketId && !(friendData.socketId in io.sockets.connected)) {
                client.emit('user_disconnect', friendData.id);
                clientPub.del(`user${friendData.id}`);
              }
            }
          });
        });
      }
    });
  });

  /**
   * Cuando el cliente hace ping al servidor, debe verificar
   * que siga activo en la base de datos de redis, en caso
   * de no estar, lo vuelve a activar
   */
  client.on('verify_conection', function (data) {
    clientPub.get(`user${client._userId}`, (err, reply) => {
      if (!reply) {
        console.log(
          `[Redis] No se pueden encontrar los amigos del usuario ${client._userId}`
        );
      } else {
        const userData = JSON.parse(reply);

        client._userId && clientPub.set(
          `user${client._userId}`, reply,
          'EX', EXPIRE_REDIS_SESSION
        );

        // notify if someone user got out
        userData.friends && userData.friends.forEach(friendId => {
          clientPub.get(`user${friendId}`, (err, replyFriend) => {
            if (replyFriend) {
              if (replyFriend.socketId && !(replyFriend.socketId in io.sockets.connected)) {
                clientPub.del(`user${friendId}`);
              } else {
                return;
              }
            }
            client.emit('user_disconnect', friendId);
          });
        });
      }
    });
  });

  /**
   * Cuando un usuario se desconecta, le emitimos un evento a cada amigo conectado
   * para que pueda re-renderizar el dom
   */
  client.on('disconnect', function(data) {
    console.log(`[User][${client._userId}]: Disconnect with ${data}`)

    if (data == 'transport error') {
      clientPub.get(`user${client._userId}`, (err, reply) => {
        if (reply) {
          const userData = JSON.parse(reply);

          userData.friends && userData.friends.forEach(friendId => {
            clientPub.get(`user${friendId}`, (err, reply) => {
              if (reply) {
                const friendData = JSON.parse(reply);

                if (friendData.socketId && friendData.socketId in io.sockets.connected) {
                  io.sockets.connected[friendData.socketId].emit('user_disconnect', userData.id);
                }
              }
            });
          });
        }
        console.log(`[User][${client._userId}]: Disconnect successfull`)
      });
    }
  });
});