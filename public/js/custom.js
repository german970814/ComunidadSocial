$(document).ready(function () {
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
})