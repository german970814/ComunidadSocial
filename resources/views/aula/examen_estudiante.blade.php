@extends('layouts.base')

@section('custom_css')
<style>
.amigos_activos {
    display: none;
}
</style>
@endsection

@section('content')
    <section class="mainContent">
        <div class="container">
            <div class="row">
                @include('layouts.title_page', ['title_page' => 'Examen: ' . $examen->get_titulo()])
            </div>
            <div class="row">
                <div id="vue">
                </div>
            </div>
        </div>
    </section>
@endsection


@section('custom_script')
<script src="{{ asset('assets/plugins/vue/vue.js') }}"></script>
<script>

function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
}

const Counter = Vue.component('counter', {
    delimiters: ['{(', ')}'],
    data: () => {
        return {
            timer: null,
            offset: 0,
            end: null,
        }
    },
    computed: {
        hours() {
            return Math.floor((this.offset % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        },
        minutes() {
            return Math.floor((this.offset % (1000 * 60 * 60)) / (1000 * 60));
        },
        seconds() {
            return Math.floor(this.offset / 1000 % 60);
        }
    },
    created() {
        var now = new Date().getTime();
        this.end = new Date('{{ $entrega->get_fecha_fin() }}'.replace(/-/g, '/')).getTime();
        this.offset = (this.end - now);
        this.timer = setInterval(this.countDown, 1000);
    },
    methods: {
        countDown() {
            this.offset = (this.end - (new Date().getTime()))

            if (this.offset <= 1) {
                clearInterval(this.timer)
                // hacer algo cuando el tiempo acaba
                window.location.href = "{{ route('aula.ver-examen', $examen->id) }}";
            }
        }
    },
    template: `
    <div class="coursesCounter">
        <div class="counterInner">
            <h3>Tiempo restante</h3>
            <div class="coursesCountStart clearfix">
                <div id="courseTimer" class="courseCountTimer timer">
                    <div class="timer-head-block"></div>
                    <div class="timer-body-block">
                        <div class="table-cell">
                            <div class="tab-val">{( hours )}</div>
                            <div class="tab-metr">horas</div>
                        </div>
                        <div class="table-cell">
                            <div class="tab-val">{( minutes )}</div>
                            <div class="tab-metr">minutos</div>
                        </div>
                        <div class="table-cell">
                            <div class="tab-val" style="opacity: 1;">{( seconds )}</div>
                            <div class="tab-metr">segundos</div>
                        </div>
                    </div>
                    <div class="timer-foot-block"></div>
                </div>
            </div>
        </div>
    </div>
    `
});

const Pregunta = Vue.component('pregunta', {
    delimiters: ['{(', ')}'],
    props: {
        pregunta: Object,
        respuesta: Object
    },
    computed: {
        isMultiple() {
            const sorted = this.pregunta.opciones.sort((opA, opB) => {
                return opA.respuesta < opB.respuesta;
            });
            return sorted[0].respuesta && sorted[1].respuesta;
        },
        respuestaCount() {
            return this.pregunta.opciones.filter(op => op.respuesta).length
        }
    },
    methods: {
        siguientePregunta() {
            this.$emit('siguientePregunta')
        },
        onInput(val, opcion) {
            // this.$emit('respuestaUpdate', this.respuesta)
            var respuesta = this.respuesta.respuestas.find(rp => rp.id == opcion.id)

            if (!respuesta) {
                respuesta = opcion.id
            }

            if (val) {
                if (this.isMultiple) {
                    if (this.respuesta.respuestas.length < this.respuestaCount) {
                        this.respuesta.respuestas.push(respuesta)
                    }
                } else {
                    this.respuesta.respuestas = [respuesta]
                }
            } else {
                var r = this.respuesta.respuestas.findIndex(rp => rp == respuesta)
                if (r + 1) {
                    this.respuesta.respuestas.splice(r, 1)
                }
            }
        }
    },
    template: `
        <div class="col-md-9 col-sm-8 col-xs-12 block pull-right">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="color-3">{( pregunta.titulo )}</h4>
                    <span>{( isMultiple ? 'Escoge una o varias respuestas' : 'Escoge una sola respuesta' )}</span>
                    <span v-if="respuestaCount > 1">({( respuestaCount )} correctas)</span>
                    <div class="respuestas-container">
                        <ul>
                            <li v-for="opcion of pregunta.opciones">
                                <input :checked="respuesta.respuestas.includes(opcion.id) ? true : false" @change="onInput($event.target.checked, opcion)" :name="'opcion'" :type="isMultiple ? 'checkbox' : 'radio'" :id="'opcion_'.concat(opcion.id)" />
                                <label :for="'opcion_'.concat(opcion.id)">{( opcion.text )}</label>
                            </li>
                        </ul>
                    </div>
                    <div style="float: right">
                        <button class="btn btn-info" type="button" @click.stop="siguientePregunta">Siguiente</button>
                    </div>
                </div>
            </div>
        </div>
    `
});

const PreguntasContainer = Vue.component('pregunta-container', {
    delimiters: ['{(', ')}'],
    props: {
        preguntas: Array,
        selected: Number
    },
    methods: {
        selectPregunta(id) {
            this.$emit('preguntaSelected', id);
        },
        getStyle(id) {
            if (id == this.selected) {
                return {
                    backgroundColor: 'aliceblue'
                }
            }
            return {}
        }
    },
    template: `
        <div class="panel panel-default courseSidebar">
            <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">Preguntas</h3>
            </div>
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    <li v-for="(pregunta, index) in preguntas">
                        <a @click.stop="selectPregunta(pregunta.id)" :style="getStyle(pregunta.id)" href="javascript:void(0)">Pregunta # {( index + 1 )}</a>
                    </li>
                </ul>
            </div>
        </div>
    `
});

new Vue({
    el: '#vue',
    delimiters: ['{(', ')}'],
    data: {
        preguntas: {!! $examen->preguntas !!}, // TODO: Quitar la respuesta
        preguntaSelected: null,
        respuestas: {!! $entrega->respuestas !!}
    },
    created() {
        let preguntas = this.preguntas.map(pr => {
            shuffleArray(pr.opciones)
            return pr
        });
        shuffleArray(preguntas)
        this.preguntas = preguntas;
        if (!this.preguntaSelected) {
            this.preguntaSelected = this.preguntas[0];
        }
    },
    methods: {
        getRespuesta() {
            var respuesta = this.respuestas.find(rp => rp.pregunta == this.preguntaSelected.id)
            if (!respuesta) {
                respuesta = {
                    pregunta: this.preguntaSelected.id,
                    respuestas: []
                }
                this.respuestas.push(respuesta)
                return respuesta
            }
            return respuesta
        }
    },
    watch: {
        respuestas: {
            handler: function() {
                $.ajax({
                    url: "{{ route('aula.guardar-respuesta-estudiante', $entrega->id) }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        respuestas: JSON.stringify(this.respuestas)
                    },
                    success: (data) => {
                        console.log(data);
                    }
                });
            },
            deep: true,
            immediate: true
        }
    },
    render(h) {
        return h('aside', {}, [
            h('div', {
                'class': 'col-md-3 col-sm-4 col-xs-12 pull-left'
            }, [
                h('div', {'class': 'rightSidebar'}, [
                    h('pregunta-container', {
                        props: {
                            preguntas: this.preguntas,
                            selected: this.preguntaSelected.id
                        },
                        on: {
                            preguntaSelected: (id) => {
                                this.preguntaSelected = this.preguntas.find(pr => pr.id == id)
                            }
                        }
                    }),
                    h('counter')
                ]),
            ]),
            h('pregunta', {
                props: {
                    pregunta: this.preguntaSelected,
                    respuesta: this.getRespuesta()
                },
                on: {
                    siguientePregunta: () => {
                        var index = this.preguntas.findIndex(pr => pr.id == this.preguntaSelected.id)
                        var newIndex = index + 1
                        if (newIndex) {
                            if (this.preguntas[newIndex]) {
                                this.preguntaSelected = this.preguntas[newIndex]
                            }
                        }
                    },
                    respuestaUpdate: (respuesta) => {  // TODO: remover esto
                        let _respuestas = this.respuestas.filter(rp => rp.pregunta != respuesta.pregunta)
                        this.respuestas = [
                            ..._respuestas,
                            respuesta
                        ]
                    }
                }
            })
        ])
        return ;
    }
});
</script>
@endsection