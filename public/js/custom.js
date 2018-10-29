$(document).ready(function () {
  function get_url(name, id) {
    return window._app_config.routes[name].replace('99', id);
  }
  window._getUrl = get_url;  // attacht to window

  /**
   * Mensajes desde el servidor
   */
  Object.keys(window._app_config.messages).forEach(code => {
    $.notify({ message: window._app_config.messages[code] }, { type: code })
  });

  /**
   * Evento para enviar una solicitud de amistad
   */
  $('a.solicitud-amistad').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      method: 'GET',
      url: window._app_config.routes.solicitarAmistad.replace('99', window._app_config.server.usuarioId),
      success: (data) => {
        if (data.code === 200) {
          $.notify({ message: data.message }, { type: 'success' })
        }
      }
    })
  })

  /**
   * Evento para enviar una solicitud de ingreso a institucion
   */
  $('a.solicitud-institucion').on('click', function (e) {
    e.preventDefault();
    var institucionId = $(this).data('institucion-id');

    $.ajax({
      method: 'GET',
      url: window._app_config.routes.solicitarIngresoInstitucion.replace('99', institucionId),
      success: (data) => {
        if (data.code === 200) {
          $.notify({ message: data.message }, { type: 'success' })
        }
      }
    })
  })

  /**
   * Evento para aceptar una solicitud de amistad
   */
  $('button.aceptar-solicitud').on('click', function (e) {
    e.preventDefault();
    $userId = $(this).data('user-id');

    $.ajax({
      method: 'GET',
      url: window._app_config.routes.aceptarSolicitudAmistad.replace('99', $userId),
      success: (data) => {
        $.notify({ message: data.message }, { type: 'success' })
      }
    })
  })

  /**
   * Evento para leer notificaciones
   */
  $('.notificacion-item.notificacion-pendiente').on('mouseover', function (e) {
    $notificacionId = $(this).data('id');

    $.ajax({
      method: 'GET',
      url: window._app_config.routes.leerNotificacion.replace('99', $notificacionId),
      success: (data) => {
        if (data.code === 200) {
          var $total = $('.functionList .cart-dropdown .shop-cart span.badge')
          $(this).removeClass('notificacion-pendiente')
          if (parseInt($total.html()) - 1) {
            $total.html(parseInt($total.html()) - 1)
          } else {
            $total.remove()
          }
        }
      }
    })
  })

  /**
   * Evento para dar like a post
   */
  $('[data-post-id]').find('.like').on('click', function (e) {
    $postId = $(this).parents('[data-post-id]').data('post-id')

    $.ajax({
      method: 'GET',
      url: window._app_config.routes.likePost.replace('99', $postId),
      success: (data) => {
        if (data.code === 200) {
          $(this).toggleClass('btn-like btn-liked')
        }
      }
    })
  });

  /**
   * Envento para comentar un post
   */
  $('[data-post-id]').find('[name="mensaje"]').on('keydown', function (e) {
    if (e.keyCode == 13) {
      e.preventDefault()
      $postId =$(this).parents('[data-post-id]').data('post-id')
  
      $.ajax({
        method: 'POST',
        url: window._app_config.routes.comentarPost.replace('99', $postId),
        data: {
          mensaje: $(this).val(),
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: (data) => {
          if (data.code === 200) {
            const base = `<dd>
              <div class="comment row-comment">
                <div class="media">
                  <div class="media-left">
                    <a href="${ window._app_config.routes.mostrarUsuario.replace(99, data.data.user_id) }">
                      <img src="#" alt="profile" width="32" height="32" class="media-object" />
                    </a>
                  </div>
                  <div class="media-body">
                    <a href="${ window._app_config.routes.mostrarUsuario.replace(99, data.data.user_id) }">
                      <h5 class="media-heading">${ window._app_config.server.loggedUserFullName }</h5>
                    </a>
                    <span>${ data.data.created_at }</span>
                  </div>
                  <div class="row post-content">
                    <div class="post">
                      <p>${ data.data.mensaje }</p>
                    </div>
                  </div>
                </div>
              </div>
            </dd>`

            $(this).parents('[data-post-id]').find('.comment-list').append(base);
            $(this).val('').blur();
          }
        }
      })
    }
  })

  /**
   * Evento para subir la foto de perfil del usuario
   */
  $('form.form-change-profile-photo').on('change', function() {
    $(this).submit();
  })

  $('textarea').each(function () {
    this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
  }).on('input', function () {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
  });

  $('.post-create-container textarea').on('input', function() {
    if (!$(this).val()) {
      $('.post-create-container button[type="submit"]').attr('disabled', true);
    } else {
      $('.post-create-container button[type="submit"]').attr('disabled', false);
    }
  });

  /**
   * Evento para previsualizar la imagen del post
   */
  $('.post-create-container input[name="photo"]').on('change', function () {
    var fileReader = new FileReader();
    fileReader.readAsDataURL($(this)[0].files[0]);

    fileReader.onload = function (event) {
      $('.post-create-container .img-preview').attr('src', event.target.result).removeClass('hidden');
    };
  })

  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
  });

  /**
   * Evento para reportar un post
   */
  $('.reportar-post').on('click', function () {
    swal({
      title: "Reportar publicación",
      text: "¿Seguro que quieres reportar esta publicación?\n\nEscríbenos una razón de por qué reportas esta publicación",
      content: 'input',
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete !== null) {
        const postId = $(this).parents('[data-post-id]').data('post-id');
      
        $.ajax({
          url: get_url('reportarPost', postId) + `?razon=${willDelete || ''}`,
          method: 'GET',
          success: (data) => {
            if (data.code === 200) {
              swal("Se ha envíado el reporte", {
                icon: "success",
              });
            } else if (data.code === 204) {
              $.notify({ message: data.message }, { type: 'info' })
            } else {
              $.notify({ message: data.message }, { type: 'danger' })
            }
          }
        });
      }
    });
  })

  /**
   * Evento para reportar un comentario
   */
  $('.reportar-comentario').on('click', function () {
    swal({
      title: "Reportar comentario",
      text: "¿Seguro que quieres reportar éste comentario?\n\nEscríbenos una razón de por qué reportas este comentario",
      content: 'input',
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete !== null) {
        const commentId = $(this).parents('[data-comment-id]').data('comment-id');

        $.ajax({
          url: get_url('reportarComentario', commentId) + `?razon=${willDelete || ''}`,
          method: 'GET',
          success: (data) => {
            if (data.code === 200) {
              swal("Se ha envíado el reporte", {
                icon: "success",
              });
            } else if (data.code === 204) {
              $.notify({ message: data.message }, { type: 'info' })
            } else {
              $.notify({ message: data.message }, { type: 'danger' })
            }
          }
        });
      }
    });
  });
})