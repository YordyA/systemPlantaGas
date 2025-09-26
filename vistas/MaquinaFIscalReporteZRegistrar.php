<?php
if (!isset($_GET['serialMaquinaFiscal']) || $_GET['serialMaquinaFiscal'] == '' || !isset($_GET['nroReporteZ']) || $_GET['nroReporteZ'] == '') {
  exit("<script>window.location.href = 'MaquinaFIscalReporteZ'</script>");
}
require_once './modulos/main.php';
require_once './modulos/dependencias.php';
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR RESUMEN REPORTE Z</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <form id="formulario" class="row">
              <div class="col-md-6">
                <?= renderInput('SERIAL MAQUINA FISCAL', '', 'text', Desencriptar($_GET['serialMaquinaFiscal']), true, 3, 'disabled'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('NRO DE REPORTE Z', '', 'text', Desencriptar($_GET['nroReporteZ']), true, 3, 'disabled'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('NRO DE FACTURA DE INICIO', 'nroFacturaDesde', 'number', '', true); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('NRO DE FACTURA DE FINAL', 'nroFacturaHasta', 'number', '', true); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('MONTO TOTAL EXENTO <strong>(VENTAS)</strong>', 'montoTotalExento', 'number', '', true, 3, 'step="0.01"'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('MONTO TOTAL BASE IMPONIBLE <strong>(VENTAS)</strong>', 'montoTotalBaseImponible', 'number', '', true, 3, 'step="0.001"'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('MONTO TOTAL EXENTO <strong>(NOTA DE CREDITO)</strong>', 'montoTotalExentoNotaCredito', 'number', '', true, 3, 'step="0.001"'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('MONTO TOTAL BASE IMPONIBLE <strong>(NOTA DE CREDITO)</strong>', 'montoTotalBaseImponibleCredito', 'number', '', true, 3, 'step="0.001"'); ?>
              </div>
              <div class="text-center">
                <button class="main-btn primary-btn btn-hover m-1">
                  <strong>REGISTRAR</strong>
                </button>
                <button type="reset" class="main-btn danger-btn btn-hover m-1">
                  <strong>CANCELAR</strong>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</section>

<script>
  facturacionMain.classList.add('active')
  MaquinaFIscalReporteZ.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL REPORTE Z SE REGISTRARA EN EL SISTEMA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST(
          'modulos/maquinasFiscales/maquinasFiscalesRegistrarReporteZ.php?serialMaquinaFiscal=<?= $_GET['serialMaquinaFiscal']; ?>&nroReporteZ=<?= $_GET['nroReporteZ']; ?>'
        )
      }
    })
  })
</script>