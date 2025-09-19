<?php
require_once './modulos/main.php';
require_once './modulos/usuarios/usuariosMain.php';
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR USUARIO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form autocomplete="off" id="formulario" class="row">
          <div class="col-md-4">
            <?= renderInput('NOMBRE DE USUARIO:', 'nombreUsuario', 'text', '', true); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('USUARIO:', 'usuario', 'text', '', true); ?>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-dark">PRIVILEGIO:</label>
              <select class="form-select form-select-lg text-bold" name="IDPrivilegio" required>
                <option value="" selected>SELECCCIONE</option>
                <?php foreach (usuariosListaPrivilegios() as $row) : ?>
                  <option value="<?= encriptar($row['IDPrivilegio']); ?>">
                    <?= $row['DescripcionPrivilegio']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <?= renderInput('CONTRASEÑA:', 'clave1', 'password', '', true); ?>
          </div>
          <div class="col-md-6">
            <?= renderInput('CONFIRMAR LA CONTRASEÑA:', 'clave2', 'password', '', true); ?>
          </div>
          <div class="text-center">
            <button type="submit" class="main-btn primary-btn btn-hover m-1">
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
</section>

<script>
  usuariosMain.classList.add('active')
  usuariosReg.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL USUARIO SERA REGISTRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/usuarios/usuariosRegistrar.php')
      }
    })
  })
</script>