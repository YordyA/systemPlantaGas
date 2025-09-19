<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR ALMACEN</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" autocomplete="off" class="row">
          <div class="col-md-12">
            <?= renderInput('DESCRIPCION DEL ALMACEN:', 'descripcionAlmacen', 'text', '', true); ?>
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
</section>

<script>
  inventarioPlantaMain.classList.add('active')
  almacenRegistrar.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL ALMACEN SERA REGISTRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/inventario/almacenRegistrar.php')
      }
    })
  })
</script>