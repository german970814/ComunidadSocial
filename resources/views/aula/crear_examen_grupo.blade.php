@extends('grupos.base')

@section('section')
    <section>
        @include('layouts.title_page', ['title_page' => isset($examen) ? 'Editar Examen' : 'Crear Exámenes'])
        <div class="row">
            <form action="{{ isset($examen) ? route('aula.actualizar-examen', $examen->id) : route('aula.guardar-examen', $grupo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-12">
                    <h3 style="overflow: inherit">Preguntas</h3>
                    {{-- <input type="hidden" name="preguntas" value="{{ old('preguntas', '') }}"> --}}
                    <div id="vue"></div>
                </div>
                <div class="col-xs-offset-5 col-xs-2">
                    <button id="submit-examen" class="btn btn-primary" type="submit">{{ isset($examen) ? 'Editar' : 'Crear' }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('custom_css')
<style>
</style>
@endsection

@section('custom_script')
<script src="{{ asset('assets/plugins/vue/vue.js') }}"></script>
<script>

const OpcionComponent = Vue.component('opcion-pregunta', {
    delimiters: ['{(', ')}'],
    props: {
        text: String,
        respuesta: Boolean
    },
    methods: {
        updateValue(value) {
            this.$emit('input', value)
        },
        updateRespuesta() {
            this.$emit('respuesta', !this.respuesta)
        }
    },
    template: `
        <li>
            <div class="col-xs-8">
                <input class="examen-opcion" :value="text" @input="updateValue($event.target.value)" />
            </div>
            <div class="col-xs-4">
                <button @click="updateRespuesta" type="button" data-original-title="Seleccionar como respuesta" data-placement="bottom" data-toggle="tooltip" :class="this.respuesta ? 'btn btn-success btn-sm' : 'btn btn-outline-success btn-sm'"><i class="fa fa-check"></i></button>
            </div>
        </li>
    `
});

const PreguntaComponent = Vue.component('nueva-pregunta', {
    data: () => {
        return {
            text: ''
        }
    },
    props: {
        titulo: String,
        opciones: Array,
    },
    methods: {
        addOpcion() {
            if (this.text.trim()) {
                this.opciones.length
                this.opciones.push(
                    {
                        id: this.opciones.length + 1,
                        text: this.text, respuesta: false
                    }
                )
                this.text = ''
                setTimeout(() => {
                    window.$('[data-toggle=tooltip]').tooltip();
                }, 1000)
            }
        },
        updateValue(value) {
            this.$emit('input', value)
        },
        changeText(val, id) {
            let option = this.opciones.find(el => el.id == id)
            option.text = val
        },
        changeRespuesta(val, id) {
            let option = this.opciones.find(el => el.id == id)
            option.respuesta = val
        }
    },
    template: `
        <div class="col-xs-12 pregunta-container">
            <div class="form-group">
                <input class="form-control border-color-1" placeholder="Título" :value="titulo" @input="updateValue($event.target.value)" />
            </div>
            <div class="col-md-8 col-xs-12">
                <ol>
                    <opcion-pregunta v-for="item in opciones" :text="item.text" :respuesta="item.respuesta" v-bind:key="item.id" @input="(val) => {changeText(val, item.id)}" @respuesta="(val) => {changeRespuesta(val, item.id)}"></opcion-pregunta>
                    <li>
                        <div class="col-xs-8">
                            <input class="examen-opcion" v-model="text" placeholder="Nueva opción" />
                        </div>
                        <div class="col-xs-4">
                            <button data-original-title="Agregar nueva opción" data-placement="bottom" data-toggle="tooltip" class="btn btn-primary btn-sm" type="button" v-on:click="this.addOpcion"><i class="fa fa-plus"></i></button>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    `
});

new Vue({
    el: '#vue',
    delimiters: ['{(', ')}'],
    data: {
        preguntas: [
            {
                opciones: [],
                titulo: '',
                id: 1,
            }
        ]
    },
    watch: {
        preguntas: {
            handler: function (val, oldVal) {
                $('input[name="preguntas"]').val(JSON.stringify(this.preguntas))
                this.validatePreguntas()
            },
            deep: true
        }
    },
    methods: {
        addPregunta() {
            this.preguntas.push({
                opciones: [],
                titulo: '',
                id: (this.preguntas.length + 1)
            })
        },
        validatePreguntas() {
            let error = false;
            for (let pregunta of this.preguntas) {
                if (pregunta.titulo.trim()) {
                    hasRespuesta = false
                    for (let opcion of pregunta.opciones) {
                        if (opcion.text.trim()) {
                            if (opcion.respuesta) {
                                hasRespuesta = true
                            }
                        } else {
                            error = true;
                            break
                        }
                    }
                    if (error) {
                        break;
                    }
                    if (!hasRespuesta) {
                        error = true;
                        break;
                    }
                } else {
                    error = true;
                    break;
                }
            }

            $('button#submit-examen').attr('disabled', error);
        }
    },
    created: function () {
        let initial = $('input[name="preguntas"]').val()
        if (initial) {
            this.preguntas = JSON.parse(initial)
        } else {
            $('button#submit-examen').attr('disabled', true);
        }
    },
    render (h) {
        return h('div', {
            'class': 'aula-preguntas'
        }, [
            h('div', {}, [
                ...this.preguntas.map((pregunta, index) => {
                    return h('nueva-pregunta', {
                        props: {
                            opciones: pregunta.opciones,
                            titulo: pregunta.titulo
                        },
                        on: {
                            input: (value) => {
                                pregunta.titulo = value
                            }
                        }
                    })
                })
            ]),
            h('div', {}, [
                h('button', {
                    'class': 'btn btn-primary',
                    domProps: {
                        type: 'button',
                    },
                    on: {
                        click: () => {
                            this.addPregunta();
                        }
                    }
                }, [
                    'Agregar pregunta'
                ])
            ])
        ])
    }
})

</script>
@endsection