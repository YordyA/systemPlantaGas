<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}
require_once './modulos/main.php';
require_once './modulos/usuarios/usuariosMain.php';

$IDUsuario = desencriptar($_GET['id']);
$consulta = usuariosVerificarXID([$IDUsuario]);
if ($consulta->rowCount() != 1) {
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
            <h2>ACTUALIZAR USUARIO: <i>(<?= $consulta['NombreUsuario']; ?>)</i></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form autocomplete="off" id="formulario" class="row">
          <div class="col-md-4">
            <?= renderInput('NOMBRE DE USUARIO:', 'nombreUsuario', 'text', $consulta['NombreUsuario'], true); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('USUARIO:', 'usuario', 'text', $consulta['Usuario'], true); ?>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-dark">PRIVILEGIO:</label>
              <select class="form-select form-select-lg text-bold" name="IDPrivilegio" required>
                <?php foreach (usuariosListaPrivilegios() as $row) : ?>
                <option value="<?= encriptar($row['IDPrivilegio']); ?>"
                  <?= ($consulta['IDPrivilegio'] == $row['IDPrivilegio'] ? 'selected' : ''); ?>>
                  <?= $row['DescripcionPrivilegio']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <p class="text-center mb-3 text-dark text-bold">
            Si desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave
            deje los campos vacíos.
          </p>
          <div class="col-md-6">
            <?= renderInput('CONTRASEÑA:', 'clave1', 'password', '', false); ?>
          </div>
          <div class="col-md-6">
            <?= renderInput('CONFIRMAR LA CONTRASEÑA:', 'clave2', 'password', '', false); ?>
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
usuariosMain.classList.add('active')
usuariosReg.classList.add('active')

formulario.addEventListener('submit', (e) => {
  e.preventDefault()
  Swal.fire({
    title: '¿ESTA SEGURO?',
    text: 'EL USUARIO SERA ACTUALIZADO',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ACEPTAR',
    cancelButtonText: 'CANCELAR'
  }).then((result) => {
    if (result.isConfirmed) {
      ajaxEnviarInformacionPOST('modulos/usuarios/usuariosActualizar.php?id=<?= $_GET['id']; ?>')
    }
  })
})
</script>