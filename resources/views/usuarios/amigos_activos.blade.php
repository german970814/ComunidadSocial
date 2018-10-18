{{-- <div class="amigos_activos">
    <div class="accordionCommon" id="accordionTwo">
        <div class="panel-group" id="accordionSecond">
            <div class="panel panel-default">
                <a class="panel-heading accordion-toggle bg-color-1 collapsed" data-toggle="collapse" data-parent="#accordionSecond" href="#collapse-s-1" aria-expanded="false">
                    <span>Amigos activos</span> <span id="num-connected-users">(0)</span>
                    <span class="iconBlock iconTransparent"><i class="fa fa-chevron-down"></i></span>
                    <span class="separator"></span>
                </a>
                <div id="collapse-s-1" class="panel-collapse collapse" aria-expanded="false" style="">
                <div class="panel-body">
                    <div class="list-group">
                        <!-- <a href="#" class="list-group-item">Hola</a> -->
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div id="logged-users"></div>
<div id="chat" style="position: fixed; bottom: 0; right: 300px;"></div>

<script>
// const Chat = Vue.component('chat', {
//     delimiters: ['{(', ')}'],
//     data () {
//         return {
//             id: 1
//         }
//     },
//     template: `
//         <div class="">
//             <div style="width: 300px;" class="" :id=" 'accordion_' + id ">
//                 <div style="margin-bottom: 0;" class="panel panel-default">
//                     <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" :data-parent=" 'accordion_' + id " :href="'#collapse-a-' + id" aria-expanded="false">
//                         <span class="">Conversación</span>
//                     </div>
//                     <div :id="'collapse-a-' + id" class="panel-collapse collapse" aria-expanded="false">
//                         <div style="height: 300px;" class="panel-body">
//                             <div style="height: 225px; overflow: scroll;">
//                                 <div>
//                                     <div style="background-color: aliceblue; border-radius: 5px; padding: 7px" class="">
//                                         <div>
//                                             <a href="#">Usuario</a>
//                                         </div>
//                                         <div>
//                                             <p>HOla</p>
//                                         </div>
//                                     </div>
//                                     <div class="pull-right">
//                                         <div>
//                                             <a href="#">Tú</a>
//                                         </div>
//                                         <div>
//                                             <p>HOla</p>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </div>
//                             <div style="position: absolute; bottom: 0; width: 90%;" class="form-group">
//                                 <input class="form-control" />
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     `
// });

// new Vue({
//     el: '#chat',
//     render(r) {
//         return r('div', {
//             style: {
//                 position: 'fixed',
//                 bottom: 0,
//                 right: '300px'
//             },
//         }, [
//             r('chat', [])
//         ]);
//     }
// });
</script>