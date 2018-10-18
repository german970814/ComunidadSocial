// https://github.com/socketio/socket.io-client/blob/master/docs/API.md

const Chat = Vue.component('chat', {
  delimiters: ['{(', ')}'],
  props: {
    id: Number
  },
  data() {
    return {
      mensaje: '',
      mensajes: []
    }
  },
  created() {
    document.addEventListener('_user_message', e => {
      if (e.detail.conversacion_id === this.id) {
        this.mensajes.push(e.detail);
        setTimeout(() => {
          this.scrollEnd();
        }, 300);
      }
    });
  
    $.ajax({
      url: window._getUrl('verConversacion', this.id),
      method: 'GET',
      success: (data) => {
        if (data.code == 200) {
          this.mensajes = data.data.mensajes;
        }
      }
    });
  },
  methods: {
    scrollEnd() {
      var container = this.$el.querySelector(`#chat-scroll-${this.id}`);
      container.scrollTop = container.scrollHeight;
    },
    renderFriendMessage(mensaje) {
      return this.$createElement('div', {
        style: {
          backgroundColor: 'aliceblue',
          borderRadius: '5px',
          padding: '7px'
        }
      }, [
        this.$createElement('div', {}, [
          this.$createElement('a', { domProps: { href: '#' } }, [
            'Usuario'
          ])
        ]),
        this.$createElement('div', {}, [
          this.$createElement('p', {}, [
            mensaje.mensaje
          ])
        ])
      ]);
    },
    renderSelfMessage(mensaje) {
      return this.$createElement('div', {
        'class': '',
        style: { textAlign: 'right' }
      }, [
        this.$createElement('div', {}, [
          this.$createElement('a', { domProps: { href: '#' } }, [
            'Tú'
          ])
        ]),
        this.$createElement('div', {}, [
          this.$createElement('p', {}, [
            mensaje.mensaje
          ])
        ])
      ]);
    },
    renderMessages() {
      return this.$createElement('div', {}, [
        ...this.mensajes.map(mensaje => {
          if (mensaje.self) {
            return this.renderSelfMessage(mensaje);
          }
          return this.renderFriendMessage(mensaje);
        })
      ]);
    },
    renderInput(h) {
      return h('div', {
        'class': 'form-group',
        style: {
          bottom: 0,
          width: '90%',
          position: 'absolute',
        }
      }, [
        h('input', {
          'class': 'form-control',
          attrs: {
            name: 'mensaje',
            'data-emojiable': 'true',
            'data-emoji-input': 'unicode'
          },
          domProps: {
            value: this.mensaje,
            'data-emojiable': 'true',
            'data-emoji-input': 'unicode'
          },
          on: {
            input: (event) => {
              this.mensaje = event.target.value;
            },
            keyup: (event) => {
              if (event.code === 'Enter') {
                $.ajax({
                  url: window._getUrl('guardarMensaje', this.id),
                  method: 'POST',
                  data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    mensaje: this.mensaje
                  },
                  success: (data) => {
                    console.log(data);
                    if (data.code == 200) {
                      this.mensajes.push({
                        self: true,
                        mensaje: this.mensaje,
                        conversacion_id: this.id,
                      });
                      setTimeout(() => {
                        this.scrollEnd();
                      }, 300);
                      this.mensaje = '';
                    }
                  }
                });
              }
            }
          }
        })
      ]);
    },
    renderHeader(h) {
      return h('div', {
        'class': 'panel-heading accordion-toggle collapsed',
        attrs: {
          'data-toggle': 'collapse',
          'data-parent': `accordion_${this.id}`,
          href: `#collapse-a-${this.id}`,
          'aria-expanded': "false"
        },
        domProps: {
          'data-toggle': 'collapse',
          'data-parent': `accordion_${this.id}`,
          href: `#collapse-a-${this.id}`,
          'aria-expanded': "false"
        },
        on: {
          click: () => {
            setTimeout(() => {
              this.scrollEnd();
            }, 100);
          }
        }
      }, [h('span', {}, ['Conversación'])]);
    },
    renderBody(h) {
      return h('div', {
        'class': 'panel-collapse collapse',
        domProps: {
          'aria-expanded': 'true'
        },
        attrs: {
          id: `collapse-a-${this.id}`
        }
      }, [
        h('div', {
          'class': 'panel-body',
          style: { height: '300px' }
        }, [
          h('div', {
            style: { height: '225px', overflow: 'scroll' },
            attrs: { id: `chat-scroll-${this.id}` }
          }, [
            this.renderMessages(h)
          ]),
          this.renderInput(h)
        ])
      ]);
    }
  },
  render(h) {
    return h('div', {
      style: {
        position: 'fixed',
        bottom: '0'
      }
    }, [
      h('div', {
        style: {
          width: '300px'
        },
        attrs: {
          id: `accordion_${this.id}`
        }
      }, [
        h('div', {
          'class': {
            'panel panel-default': true
          },
          style: {
            marginBottom: 0
          }
        }, [this.renderHeader(h), this.renderBody(h)])
      ])
    ]);
  }
  // template: `
  //   <div style="position: fixed; bottom: 0; right: 300px;" class="">
  //     <div style="width: 300px;" class="" :id=" 'accordion_' + id ">
  //       <div style="margin-bottom: 0;" class="panel panel-default">
  //         <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" :data-parent=" 'accordion_' + id " :href="'#collapse-a-' + id" aria-expanded="false">
  //           <span class="">Conversación</span>
  //         </div>
  //         <div :id="'collapse-a-' + id" class="panel-collapse collapse" aria-expanded="false">
  //           <div style="height: 300px;" class="panel-body">
  //             <div style="height: 225px; overflow: scroll;">
  //               {( renderMessages() )}
  //             </div>
  //             <div style="position: absolute; bottom: 0; width: 90%;" class="form-group">
  //               <input v-model="mensaje" class="form-control" />
  //             </div>
  //           </div>
  //         </div>
  //       </div>
  //     </div>
  //   </div>
  // `
});

const LoggedFriends = Vue.component('logged-friends', {
  delimiters: ['{(', ')}'],
  data () {
    return {
      friends: [],
    }
  },
  created () {
    try {
      const socket = io.connect('http://127.0.0.1:3000/');

      socket.on('connect', (data) => {
        // console.log('connected');
        socket.emit('add user', {
          'id': window._app_config.server.loggedUserId,
          'name': window._app_config.server.loggedUserFullName
        });
      });

      socket.on('connected_users', (data) => {
        // console.log(data);
        if (!this.friends.find(friend => friend.id == data.id)) {
          this.friends.push(data);
        }
      });

      socket.on('ping', function() {
        socket.emit('verify_conection', {
          'id': window._app_config.server.loggedUserId,
          'name': window._app_config.server.loggedUserFullName
        });
      });

      socket.on('user_message', (data) => {
        console.log(data);
        let userMessageEvent = new CustomEvent('_user_message', {
          detail: data
        });
        document.dispatchEvent(userMessageEvent);
      });

      socket.on('user_disconnect', (data) => {
        // console.log('user discconected')
        var user_in_friends = this.friends.findIndex(friend => friend.id == data);
        if (user_in_friends + 1) {
          this.friends.splice(user_in_friends, 1);
        }
      });

      socket.on('connect_error', (data) => {
        socket.close();  // cierra la conexión
      });
    } catch (exception) {
      // 
      console.log(exception)
    }
  },
  methods: {
    initChat(id) {
      this.$emit('initChat', id);
    }
  },
  template: `
    <div class="amigos_activos">
      <div class="accordionCommon" id="accordionTwo">
        <div class="panel-group" id="accordionSecond">
          <div class="panel panel-default">
            <a class="panel-heading accordion-toggle bg-color-1 collapsed" data-toggle="collapse" data-parent="#accordionSecond" href="#collapse-s-1" aria-expanded="false">
              <span>Amigos activos</span> <span id="num-connected-users">({( friends.length )})</span>
              <span class="iconBlock iconTransparent"><i class="fa fa-chevron-down"></i></span>
              <span class="separator"></span>
            </a>
            <div id="collapse-s-1" class="panel-collapse collapse" aria-expanded="false" style="">
              <div class="panel-body">
                <div class="">
                  <a @dblclick="initChat(friend.id)" v-for="friend of friends" href="#" class="list-group-item">{( friend.name )}</a>
                  <a v-if="friends.length == 0" href="javascript:void(0)" class="list-group-item">No tienes amigos conectados</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `
});

new Vue({
  el: '#logged-users',
  data() {
    return {
      chats: []
    }
  },
  render(h) {
    return h('div', [
      h('logged-friends', {
        on: {
          initChat: (id) => {
            $.ajax({
              url: window._getUrl('getConversacion', id),
              method: 'POST',
              data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
              },
              success: (data) => {
                console.log(data);
                if (data.code == 200) {
                  if (!this.chats.find(chat => chat.id == data.data.id)) {
                    this.chats.push({
                      id: data.data.id
                    });
                  }
                }
              }
            });
          }
        }
      }, []),
      ...this.chats.map((chat, ind) => {
        return h('chat', {
          style: {
            right: `${300 * (ind + 1)}px`
          },
          props: {
            id: chat.id
          }
        }, []);
      })
    ]);
  }
});


// try {
//   var socket = io.connect('http://127.0.0.1:3000/');
//   var friends = [];
  
//   socket.on('connect', function(data) {
//     // console.log('connected');
//     socket.emit('add user', {
//       'id': window._app_config.server.loggedUserId,
//       'name': window._app_config.server.loggedUserFullName
//     });
//   });
  
//   socket.on('connected_users', function(data) {
//     if (!friends.find(friend => friend.id == data.id)) {
//       friends.push(data);

//       $('#num-connected-users').html(`(${friends.length})`);
//       $('.list-group').html(friends.map(el => {
//         return `<a href="#" class="list-group-item">${el.name}</a>`
//       }).join(''));
//     }
//   })

//   socket.on('user_disconnect', function(data) {
//     console.log('user discconected')
//     var user_in_friends = friends.findIndex(friend => friend.id == data);
//     if (user_in_friends + 1) {
//       friends.splice(friends.findIndex, 1);  // review error
//     }

//     $('#num-connected-users').html(`(${friends.length})`);
//     $('.list-group').html(friends.map(el => {
//       return `<a href="#" class="list-group-item">${el.name}</a>`
//     }).join(''));
//   })

//   socket.on('connect_error', function (data) {
//     socket.close();  // cierra la conexión
//   });
// } catch (exception) {
//   // 
//   console.log(exception)
// }
