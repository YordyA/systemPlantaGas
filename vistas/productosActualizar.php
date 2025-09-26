<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}
require_once './modulos/main.php';
require_once './modulos/productos/productosMain.php';

$IDCisterna = desencriptar($_GET['id']);
$consulta = ProductosListaID([$IDCisterna]);
if ($consulta->rowCount() != 1) {
  exit('<script>window.location.href = document.referrer</script>');
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
$idEnteEncriptado = encriptar($IDCisterna);
?>

<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ACTUALIZANDO: <i>(<?= $consulta['DescripcionTipo'] .' '. $consulta['DescripcionProducto']; ?>)</i></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" class="row" autocomplete="off">
  
                    <div class="col-md-12">
            <div class="mb-3">
              <label class="form-label text-dark">Precio de Venta</label>
              <input type="text" class="form-control form-control-lg" name="precio" value="<?= $consulta['PrecioVenta']; ?>" step="0.01" required>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="main-btn primary-btn btn-hover m-1">
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
  productosPresentacion.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PRECIO SERA ACTUALIZADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/productos/productosActualizar.php?id=<?= $_GET['id']; ?>')
      }
    })
  })
</script>