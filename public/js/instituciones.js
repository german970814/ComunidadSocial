$(document).ready(function () {
  if ($('[name="departamento"]').val()) {
    $.ajax({
      url: window._app_config.routes.departamentoMunicipios.replace('99', $('[name="departamento"]').val()),
      method: 'GET',
      success: (data) => {
        if (data.code === 200 && data.data.length) {
          $('[name="municipio_id"]').html('<option value="">Selecciona una opción</option>' + data.data.map(obj => {
            return `<option value="${obj.id}">${obj.nombre.toUpperCase()}</option>`;
          }).join('')).val($('[name="municipio_id"]').attr('value'));
        } else if (!data.data.length) {
          $('[name="municipio_id"]').html('<option value="">Nada para escoger</option>');
        }
      }
    })
  }

  $('[name="departamento"]').on('change', function (e) {
    $.ajax({
      url: window._app_config.routes.departamentoMunicipios.replace('99', $(this).val()),
      method: 'GET',
      success: (data) => {
        if (data.code === 200 && data.data.length) {
          $('[name="municipio_id"]').html('<option value="">Selecciona una opción</option>' + data.data.map(obj => {
            return `<option value="${obj.id}">${obj.nombre.toUpperCase()}</option>`;
          }).join(''));
        } else if (!data.data.length) {
          $('[name="municipio_id"]').html('<option value="">Nada para escoger</option>');
        }
      }
    })
  })
});