<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}
require_once './modulos/main.php';
require_once './modulos/inventario/inventarioMain.php';
$IDAlmacen = desencriptar($_GET['id']);
$consulta = almacenVerificarXID([$IDAlmacen]);
if ($consulta->rowCount() == 0) {
  exit('<script>window.location.href = document.referrer</script>');
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ACTUALIZAR ALMACEN: <i>(<?= $consulta['DescripcionAlmacen']; ?>)</i></h2>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" autocomplete="off">
          <div class="row">
            <div class="col-md-12">
              <?= renderInput('DESCRIPCION DEL ALMACEN:', 'descripcionAlmacen', 'text', $consulta['DescripcionAlmacen'], true); ?>
            </div>
          </div>
          <div class="text-center">
            <button class="main-btn primary-btn btn-hover m-1">
              <strong>ACTUALIZAR</strong>
            </button>
            <button type="reset" class="main-btn danger-btn btn-hover m-1" onclick="volver()">
              <strong>CANCELAR</strong>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
  inventarioPlantaMain.classList.add('active')
  inventarioPlantaList.classList.add('active')

formulario.addEventListener('submit', (e) => {
  e.preventDefault()
  Swal.fire({
    title: '¿ESTA SEGURO?',
    text: 'EL ALMACEN SERA ACTUALIZADO',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ACEPTAR',
    cancelButtonText: 'CANCELAR'
  }).then((result) => {
    if (result.isConfirmed) {
      ajaxEnviarInformacionPOST('modulos/inventario/almacenActualizar.php?id=<?php echo $_GET['id']; ?>')
    }
  })
})
</script>