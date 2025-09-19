<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>MI PERFIL: <i>(<?= $_SESSION['PlantaGas']['nombreUsuario']; ?>)</i></h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form autocomplete="off" id="formulario" class="row">
          <div class="col-md-6">
            <?= renderInput('NOMBRE DE USUARIO:', 'nombreUsuario', 'text', $_SESSION['PlantaGas']['nombreUsuario'], true); ?>
          </div>
          <div class="col-md-6">
            <?= renderInput('USUARIO:', 'usuario', 'text', $_SESSION['PlantaGas']['usuario'], true); ?>
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
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PERFIL SERA ACTUALIZADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST(
          'modulos/usuarios/usuariosActualizar.php?id=<?= encriptar($_SESSION['PlantaGas']['IDUsuario']); ?>'
        )
      }
    })
  })
</script>