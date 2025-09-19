<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}

require_once './modulos/main.php';
require_once './modulos/despachos/despachosMain.php';

$IDDespachoResumen = desencriptar($_GET['id']);
$consulta = despachosConsultarDespachoDetalladoXID([$IDDespachoResumen]);
if ($consulta->rowCount() == 0) {
  exit('<script>window.location.href = document.referrer</script>');
}
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURAR DESPACHO NRO - <?= generarCeros($consulta[0]['NroNota'], 5) ?></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <form id="formulario" class="row">
              <div class="col-md-3">
                <?= renderInput('FECHA FACTURA:', 'fechaf', 'date', '', true); ?>
              </div>
              <div class="col-md-3">
                <?= renderInput('SERIE DEL TALONARIO:', 'serie', 'text', '', true); ?>
              </div>
              <div class="col-md-3">
                <?= renderInput('NRO DE FACTURA:', 'nroFactura', 'text', '', true); ?>
              </div>
              <div class="col-md-3">
                <?= renderInput('NRO DE CONTROL:', 'nroControl', 'text', '', true); ?>
              </div>
              <hr>
              <div class="col-md-12">
                <div class="table-wrapper table-responsive">
                  <table class="table text-center" id="tablaMain">
                    <thead>
                      <tr>
                        <th class="text-center">
                          <h6>DESCRIPCION</h6>
                        </th>
                        <th class="text-center">
                          <h6>CANT (KG) DESPACHADOS</h6>
                        </th>
                        <th class="text-center">
                          <h6>CANT (KG) A FACTURAR</h6>
                        </th>
                        <th class="text-center">
                          <h6>PRECIO UNITARIO EN BS (SIN IVA)</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="tablaInfo">
                      <?php foreach ($consulta as $row): ?>
                        <tr>
                          <th class="text-center">
                            <?= ($row['DescripcionPresentacion'] .' - '. $row['DescripcionProducto']) ?>
                          </th>
                          <th class="text-center">
                            <div class="m-1">
                              <input class="form-control text-center text-bold" type="text"
                                value="<?= (round($row['CantDesp'], 2)) ?>" readonly disabled>
                            </div>
                          </th>
                          <th class="text-center">
                            <div class="m-1">
                              <input class="form-control text-center text-bold" type="text" name="cant[]"
                                value="<?= (round($row['CantDesp'], 2)) ?>">
                            </div>
                          </th>
                          <th class="text-center">
                            <div class="m-1">
                              <input class="form-control" type="number" name="precioBS[]" step="0.001" required>
                            </div>
                          </th>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <hr>
              <div class="text-center">
                <button type="submit" class="main-btn primary-btn btn-hover m-1">
                  <strong>REGISTRAR</strong>
                </button>
                <button type="reset" class="main-btn danger-btn btn-hover m-1" onclick="volver()">
                  <strong>CANCELAR</strong>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  despachosMain.classList.add('active')
  despachosReportMain.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL DESPACHO SERA FACTURADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/despachos/despachosFacturar.php?id=<?= $_GET['id']; ?>')
      }
    })
  })
</script>