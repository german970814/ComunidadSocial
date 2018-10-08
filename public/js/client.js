// https://github.com/socketio/socket.io-client/blob/master/docs/API.md

try {
  var socket = io.connect('http://127.0.0.1:3000/');
  var friends = [];
  
  socket.on('connect', function(data) {
    console.log('connected');
    socket.emit('add user', {
      'usuario_id': window._app_config.server.loggedUserId,
      'full_name': window._app_config.server.loggedUserFullName
    });
  });
  
  socket.on('connected_users', function(data) {
    // console.log(data);
    if (!friends.find(el => el.usuario_id == data.usuario_id)) {
      friends.push(data);

      $('#num-connected-users').html(`(${friends.length})`);
      $('.list-group').html(friends.map(el => {
        return `<a href="#" class="list-group-item">${el.name}</a>`
      }).join(''));
    }
  })

  socket.on('user_disconect', function(data) {
    console.log('user discconected')
    var user_in_friends = friends.findIndex(el => el.usuario_id == data);
    if (user_in_friends + 1) {
      // delete friends[user_in_friends];
      friends.splice(friends.findIndex, 1);
    }

    $('#num-connected-users').html(`(${friends.length})`);
    $('.list-group').html(friends.map(el => {
      return `<a href="#" class="list-group-item">${el.name}</a>`
    }).join(''));
    // console.log(data)
  })

  socket.on('connect_error', function (data) {
    // pass
    socket.close()  // cierra la conexión
  })
} catch (exception) {
  // 
}
