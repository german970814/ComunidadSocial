// https://github.com/socketio/socket.io-client/blob/master/docs/API.md

const Chat = Vue.component('chat', {
  delimiters: ['{(', ')}'],
  props: {
    id: Number, name: String
  },
  data() {
    return {
      mensaje: '',
      mensajes: [],
      next: '',
      requesting: false,
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
      success: (response) => {
        if (response.code == 200) {
          let responseData = response.data.data;
          responseData.reverse();
          this.mensajes = responseData;
          this.next = response.data.next_page_url;
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
        // this.$createElement('div', {}, [
        //   this.$createElement('a', { domProps: { href: '#' } }, [
        //     this.name
        //   ])
        // ]),
        this.$createElement('div', {}, [
          this.$createElement('p', { style: { margin: 0 } }, [
            mensaje.mensaje
          ])
        ])
      ]);
    },
    renderSelfMessage(mensaje) {
      return this.$createElement('div', {
        'class': '',
        style: { textAlign: 'right', padding: '7px' }
      }, [
        // this.$createElement('div', {}, [
        //   this.$createElement('a', { domProps: { href: '#' } }, [
        //     'Tú'
        //   ])
        // ]),
        this.$createElement('div', {}, [
          this.$createElement('p', { style: { margin: 0 } }, [
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
        'class': 'bg-color-3 panel-heading accordion-toggle collapsed',
        style: {
          color: 'white'
        },
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
      }, [
        h('span', { style: { cursor: 'pointer' } }, [this.name]),
        h('span', {
          style: {
            float: 'right',
            cursor: 'pointer',
            fontWeight: 'bold',
          },
          on: {
            click: () => {
              this.$emit('closeChat');
            }
          }
        }, ['X']),
      ]);
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
            attrs: { id: `chat-scroll-${this.id}` },
            on: {
              scroll: (e) => {
                let element = this.$el.querySelector(`#chat-scroll-${this.id}`);
                if (element.scrollTop < 15) {
                  console.log('Ve trayendo datos')
                  if (!this.requesting && this.next) {
                    this.requesting = true;
                    $.ajax({
                      url: this.next,
                      method: 'GET',
                      success: (response) => {
                        if (response.code == 200) {
                          console.log(response)
                          this.next = response.data.next_page_url;
                          let responseData = response.data.data;
                          responseData.reverse();
                          this.mensajes.splice(0, 0, ...responseData);
                          this.requesting = false;
                        }
                      }
                    });
                  }
                }
              }
            }
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
      'class': 'chat-container',
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
        this.$emit('newUserMessage', data);
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
  created() {
    let chats = window.sessionStorage.getItem('_chatsIds') || '';
    window.sessionStorage.removeItem('_chatsIds');

    if (chats) {
      try {
        chats = JSON.parse(chats);
        $(document).ready(() => {
          chats.forEach(chatObj => this.addChat(chatObj))
        });
      } catch (exception) {
        console.log(exception);
      }
    }
  },
  methods: {
    refreshSessionChats() {
      window.sessionStorage.setItem(
        '_chatsIds',
        JSON.stringify(this.chats.map(chat => {
          return {
            id: chat.id, name: chat.name
          }
        }))
      );
    },
    addChat(data) {
      if (!this.chats.find(chat => chat.id == data.id)) {
        let chatId = parseInt(data.id);
        if (chatId) {
          this.chats.push({
            id: chatId, name: data.name
          });
          this.refreshSessionChats();
          return true;
        }
      }
      return false;
    },
    closeChat(chat) {
      let chatExists = this.chats.findIndex(ch => ch.id == chat.id);
      if (chatExists + 1) {
        this.chats.splice(chatExists, 1);
        this.refreshSessionChats();
      }
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
                  this.addChat({
                    id: data.data.id,
                    name: data.data.name
                  });
                }
              }
            });
          },
          newUserMessage: (data) => {
            let userMessageEvent = new CustomEvent('_user_message', {
              detail: data
            });
            let chatExists = this.chats.find(chat => chat.id == data.conversacion_id);
            if (chatExists) {
              document.dispatchEvent(userMessageEvent);
            } else {
              this.addChat({
                id: data.conversacion_id,
                name: data.name
              })
            }
          }
        }
      }, []),
      ...this.chats.map((chat, ind) => {
        return h('chat', {
          style: {
            right: `${300 * (ind + 1)}px`
          },
          props: {
            id: chat.id, name: chat.name
          },
          on: {
            closeChat: () => {
              this.closeChat(chat);
            }
          }
        }, []);
      })
    ]);
  }
});
