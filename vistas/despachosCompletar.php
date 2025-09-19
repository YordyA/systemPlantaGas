<?php
if (!isset($_SESSION['despacho']['detalle'])) {
  exit('<script>window.location.href = document.referrer</script>');
}

require_once './modulos/main.php';
require_once './modulos/despachos/despachosMain.php';

$html = '';
foreach ($_SESSION['despacho']['detalle'] as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['codigo'] . '</td>';
  $html .= '<td>' . $row['descripcion'] . '</td>';
  $html .= '<td>' . number_format($row['cantidad'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['precioVenta'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['cantidad'] * $row['precioVenta'], 2, ',', '.') . '</td>';
  $html .= '</tr>';
}
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-10">
          <div class="title mb-30">
            <h2>COMPLETAR DESPACHO</h2>
          </div>
        </div>
        <div class="col-md-2">
          <div class="mb-30">
            <button class="btn btn-warning form-control" onclick="volver()">ATRÁS</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <button type="submit" class="btn btn-success form-control form-control-lg">
                REGISTRAR
              </button>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <button type="reset" id="btnCancelar" class="btn btn-danger form-control form-control-lg">
                CANCELAR
              </button>
            </div>
          </div>
          <hr>
          <div class="col-md-12">
            <?= renderInput('CLIENTE:', '', 'text', '', true, 3, 'id="busCliente"'); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('CEDULA CHOFER:', 'choferCedula', 'text', '', true); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('CHOFER:', 'chofer', 'text', '', true); ?>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-dark">TIPO DESPACHO</label>
              <select class="form-select form-select-lg text-bold" name="IDTipoDesp" required>
                <option value="" selected>SELECCIONE</option>
                <?php foreach (despachosTiposLista() as $row) : ?>
                  <option value="<?= encriptar($row['IDTipoDespacho']); ?>"><?= $row['DescripcionTipoDesp']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <?= renderInput('OBSERVACION:', 'observacion', 'textarea', '', true, 3); ?>
          </div>
          <hr>
          <div class="col-md-12">
            <div class="table-wrapper table-responsive">
              <table class="table text-center">
                <thead>
                  <tr>
                    <th>
                      <h6>CODIGO</h6>
                    </th>
                    <th>
                      <h6>DESCRICION</h6>
                    </th>
                    <th>
                      <h6>CANT</h6>
                    </th>
                    <th>
                      <h6>P/U</h6>
                    </th>
                    <th>
                      <h6>SUBTOTAL</h6>
                    </th>
                  </tr>
                </thead>
                <tbody><?= $html; ?></tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
  despachosMain.classList.add('active')

  let IDClienteTemp

  const peticionesPOST = async (url, form, modal) => {
    const respuesta = await peticionAjaxPOST(url, form)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      form.reset()
      window.open(respuesta.url, '_blank')
    }
  }

  const peticionesGET = async (url) => {
    const respuesta = await peticionAjaxGET(url)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      setTimeout(() => {
        window.location.href = document.referrer
      }, 2000)
    }
  }

  $(document).ready(function() {
    $('#busCliente').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'modulos/despachos/despachosBuscarClientes.php',
          data: {
            buscador: request.term
          },
          dataType: 'JSON',
          success: function(data) {
            response(data)
          }
        })
      },
      minLength: 1,
      select: function(event, ui) {
        IDClienteTemp = ui.item.value
        busCliente.value = ui.item.label
        return false
      }
    })
  })

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL DESPACHO SERA REGISTRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionesPOST('modulos/despachos/despachosRegistrar.php?id=' + IDClienteTemp, formulario)
      }
    })
  })

  btnCancelar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL DESPACHO SERA CANCELADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionesGET('modulos/despachos/despachosCancelar.php')
      }
    })
  })
</script>