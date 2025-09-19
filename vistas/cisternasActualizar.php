<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}
require_once './modulos/main.php';
require_once './modulos/cisternas/cisternasMain.php';

$IDCisterna = desencriptar($_GET['id']);
$consulta = cisternasVerificarXID([$IDCisterna]);
if ($consulta->rowCount() != 1) {
  exit('<script>window.location.href = document.referrer</script>');
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
$idEnteEncriptado = encriptar($consulta['TipoCisterna']);
?>

<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ACTUALIZAR CISTERNA: <i>(<?= $consulta['Modelo']; ?>)</i></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" class="row" autocomplete="off">
          <div class="col-md-4">
            <?= renderInput('Nro de Identificacion (MODELO):', 'modelo', 'text', $consulta['Modelo'], true); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('Capacidad (LITROS):', 'capacidad', 'number', $consulta['Capacidad'], true, 'step="0.01"'); ?>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-dark">Tipo</label>
              <select class="form-select form-select-lg text-bold" name="tipo" id="tipo" required>
                <option value="" <?= ($idEnteEncriptado === '') ? 'selected' : ''; ?>>SELECCIONE</option>
                <option value="<?php echo encriptar(1); ?>" <?= ($idEnteEncriptado === encriptar(1)) ? 'selected' : ''; ?>>Propia</option>
                <option value="<?php echo encriptar(2); ?>" <?= ($idEnteEncriptado === encriptar(2)) ? 'selected' : ''; ?>>Privada</option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label class="form-label text-dark">Propietario</label>
              <input type="text" class="form-control form-control-lg" name="propietario" value="<?= $consulta['EmpresaC']; ?>" required>
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
  flotaMain.classList.add('active')
  cisternasLista.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PRODUCTOR SERA ACTUALIZADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/cisternas/cisternasActualizar.php?id=<?= $_GET['id']; ?>')
      }
    })
  })
</script>