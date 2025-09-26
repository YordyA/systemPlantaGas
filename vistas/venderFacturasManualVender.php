<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit("<script>window.location.href = document.referrer</script>");
}

require_once './modulos/main.php';
require_once './modulos/reportes/reportes_main.php';

$IIDResumenVenta = Desencriptar($_GET['id']);
$consulta = consultarVentaPorNventa([$IIDResumenVenta]);
if ($consulta->rowCount() == 0) {
  exit("<script>window.location.href = document.referrer</script>");
}
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
if (isset($_SESSION['facturaManual']['detalle'])) {
  unset($_SESSION['facturaManual']['detalle']);
}

if ($consulta[0]['EstadoFactura'] != 3) {
  exit("<script>window.location.href = document.referrer</script>");
}

foreach ($consulta as $row) {
  $_SESSION['facturaManual']['detalle'][$row['IDDetalleVenta']] = [
    'IDProducto'    => $row['IDProducto'],
    'IDAlicuota'    => $row['Alicuota'],
    'codigo'        => $row['Codigo'],
    'descripcion'   => $row['DescripcionTipo']. ' ' . $row['DescripcionProducto'],
    'precio'        => 0,
    'cantidad'      => $row['Cantidad']
  ];
}
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURAR NOTA DE ENTREGA NRO: <i><?php echo $consulta[0]['NVentaResumen']; ?></i></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row">
              <form id="formDatosFactura" class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <button type="submit" class="btn btn-success form-control">REGISTRAR</button>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <button type="button" class="btn btn-danger form-control" id="btnCancelar">CANCELAR</button>
                  </div>
                </div>
                <hr>
                <div class="col-md-6">
                  <?php echo renderInput('RIF / CEDULA:', 'rifCedula', 'text', $consulta[0]['RifCliente'], true, 'readonly'); ?>
                </div>
                <div class="col-md-6">
                  <?php echo renderInput('RAZON SOCIAL:', 'rifCedula', 'text', $consulta[0]['NombreCliente'], true, 3, 'readonly'); ?>
                </div>
                <div class="col-md-4">
                  <?php echo renderInput('FECHA FACTURA:', 'fecha', 'date', '', true); ?>
                </div>
                <div class="col-md-4">
                  <?php echo renderInput('SERIE:', 'serie', 'text', '', true); ?>
                </div>
                <div class="col-md-4">
                  <?php echo renderInput('NRO DE FACTURA:', 'nroFactura', 'number', '', true); ?>
                </div>
                <hr>
                <div class="col-md-3">
                  <?php echo renderInput('EFECTIVO:', 'efectivo', 'number', 0, true, 3, 'step="0.01"'); ?>
                </div>
                <div class="col-md-3">
                  <?php echo renderInput('TARJETA:', 'tarjeta', 'number', 0, true,  3, 'step="0.01"'); ?>
                </div>
                <div class="col-md-3">
                  <?php echo renderInput('BIOPAGO:', 'biopago', 'number', 0, true,  3, 'step="0.01"'); ?>
                </div>
                <div class="col-md-3">
                  <?php echo renderInput('TRANSFERENCIA:', 'transferencia', 'number', 0, true, 3, 'step="0.01"'); ?>
                </div>
              </form>
              <hr>
              <div class="col-md-12">
                <div class="table-wrapper table-responsive">
                  <table class="table text-center">
                    <thead>
                      <tr>
                        <th class="text-center">
                          <h6>CODIGO PRODUCTO</h6>
                        </th>
                        <th class="text-center">
                          <h6>DESCRIPCION DEL PRODUCTO</h6>
                        </th>
                        <th class="text-center">
                          <h6>CANTIDAD</h6>
                        </th>
                        <th class="text-center">
                          <h6>PRECIO</h6>
                        </th>
                        <th class="text-center">
                          <h6>SUBTOTAL</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="tablaInfoTemp"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalEditPrecio" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          INGRESAR PRECIO
        </h5>
      </div>
      <form id="formAjaxPrecio">
        <div class="modal-body">
          <?php echo renderInput('PRECIO EN BOLIVARES <strong>(SIN IVA)</strong>', 'precio', 'number', '', true, 3, 'step="0.01"'); ?>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-danger form-control" data-bs-dismiss="modal">CERRAR</button>
          <button type="submit" class="btn btn-success form-control">INGRESAR</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  facturacionMain.classList.add('active')

  let IDTemporal
  const listaTempProductos = async () => {
    tablaInfoTemp.innerHTML = await penticionAjaxGET('modulos/facturacionManual/facturacionManualListaTemp.php')
  }

  const enviarDatosGet = async (url) => {
    const respuesta = await penticionAjaxGET(url)
    alertas(respuesta)
    if (respuesta.tipo == 'success') {
      listaTempProductos()
      formAjaxPrecio.reset()
      $('#modalEditPrecio').modal('hide')
    }
  }

  const enviarDatosPost = async (url, form) => {
    const respuesta = await enviarInformacionPOST(url, form)
    alertas(respuesta)
    if (respuesta.tipo == 'success') {
      form.reset()
    }
  }

  $(document).on('click', '.btnPrecio', function() {
    IDTemporal = this.value
    $('#modalEditPrecio').modal('show')
    $('#modalEditPrecio').on('shown.bs.modal', function() {
      formAjaxPrecio.precio.focus()
    })
  })

  formAjaxPrecio.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PRECIO SERA AGREGADO A LA FACTURA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosGet('modulos/facturacionManual/facturacionManualEditPrecio.php?id=' + IDTemporal + '&precio=' +
          formAjaxPrecio.precio.value)
      }
    })
  })

  btnCancelar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA FACTURACION SERA CANCELADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosGet('modulos/facturacionManual/faturacionManualCancelar.php')
      }
    })
  })

  formDatosFactura.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA FACTURA SERA REGISTRADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosPost('modulos/facturacionManual/facturacionManualRegistrar.php?id=<?php echo $_GET['id']; ?>',
          formDatosFactura)
      }
    })
  })


  listaTempProductos()
</script>