@extends('usuarios.base_profile')

@section('section')
    <section>
        <div id="mensajes"></div>
    </section>
@endsection

@section('custom_script')
<script>

const Conversaciones = Vue.component('conversaciones', {
    delimiters: ['{(', ')}'],
    props: { conversaciones: Array },
    template: `
        <div class="col-xs-4">
            <div class="panel panel-default conversaciones-tab courseSidebar">
                <div class="panel-heading bg-color-1 border-color-1">
                    <div class="panel-title">Conversaciones</div>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a v-for="conversacion of conversaciones" href="javascript:void(0)" class="list-group-item">{( conversacion.name )}</a>
                    </div>
                </div>
            </div>
        </div>
    `
});

const Chat = Vue.component('chat', {
    delimiters: ['{(', ')}'],
    data() {
        return { mensaje: '' }
    },
    props: {
        mensajes: Array, id: Number
    },
    created() {
        setTimeout(() => {
            this.scrollEnd()
        }, 1000);
    },
    methods: {
        scrollEnd() {
            var container = this.$el.querySelector('#chat-container');
            container.scrollTop = container.scrollHeight;
        },
        renderHeader() {
            return this.$createElement('div', { 'class': 'panel-heading' }, [
                this.$createElement('div', {'class': 'panel-title'}, ['German Alzate'])
            ]);
        },
        renderBody() {
            return this.$createElement('div', {'class': 'panel-body'}, [
                this.renderMensajes(),
                this.renderInput()
            ]);
        },
        renderMensaje(mensaje) {
            return this.$createElement('div', {
                style: {
                    backgroundColor: 'aliceblue',
                    borderRadius: '5px',
                    padding: '7px'
                }
            }, [
                this.$createElement('div', {}, [
                    this.$createElement('p', { style: { margin: 0 } }, [
                        mensaje.mensaje
                    ])
                ])
            ]);
        },
        renderSelfMensaje(mensaje) {
            return this.$createElement('div', {
                style: {
                    textAlign: 'right', padding: '7px'
                }
            }, [
                this.$createElement('div', {}, [
                    this.$createElement('p', { style: { margin: 0 } }, [
                        mensaje.mensaje
                    ])
                ])
            ]);
        },
        renderMensajes() {
            return this.$createElement('div', {
                attrs: {
                    id: 'chat-container'
                },
                style: {
                    height: '400px',
                    overflowY: 'scroll'
                }
            }, this.mensajes.map(mensaje => {
                if (mensaje.self) {
                    return this.renderSelfMensaje(mensaje);
                }
                return this.renderMensaje(mensaje);
            }));
        },
        renderInput() {
            return this.$createElement('div', {
                'class': 'form-group',
                style: {
                    // bottom: 0,
                    marginTop: '15px',
                    // position: 'absolute',
                }
            }, [
                this.$createElement('input', {
                    'class': 'form-control',
                    style: {
                        border: '1px solid #ccc'
                    },
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
        renderContent() {
            return this.$createElement('div', {
                'class': 'col-xs-8'
            }, [
                this.$createElement('div', {
                    'class': 'panel panel-default'
                }, [
                    this.renderHeader(),
                    this.renderBody()
                ])
            ]);
        },
    },
    render(h) {
        return h('div', {}, [
            this.renderContent()
        ]);
    }
});

new Vue({
    el: '#mensajes',
    delimiters: ['{(', ')}'],
    data() {
        return {
            next: '',
            mensajes: [],
            conversacionSelected: 0,
            conversaciones: {!! $usuario->conversaciones->toJson() !!}
        }
    },
    created() {
        this.conversacionSelected = this.conversaciones[0].id;

        $(document).ready(() => {
            $.ajax({
                url: window._getUrl('verConversacion', this.conversacionSelected),
                method: 'GET',
                success: (response) => {
                    console.log(response)
                    if (response.code == 200) {
                        let responseData = response.data.data;
                        responseData.reverse();
                        this.mensajes = responseData;
                        this.next = response.data.next_page_url;
                    }
                }
            });
        });
    },
    render(h) {
        return h('section', {'class': 'row'}, [
            h('conversaciones', {
                props: {
                    conversaciones: this.conversaciones
                }
            }),
            h('chat', {
                props: {
                    mensajes: this.mensajes,
                    id: this.conversacionSelected
                }
            }, [])
        ]);
    }
});
</script>
@endsection
