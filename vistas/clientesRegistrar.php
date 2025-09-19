<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR CLIENTE</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" class="row" autocomplete="off">
          <div class="col-md-4">
            <?= renderInput('R.I.F / CEDULA:', 'rifCedula', 'text', '', true); ?>
          </div>
          <div class="col-md-8">
            <?= renderInput('RAZON SOCIAL:', 'razonSocial', 'text', '', true); ?>
          </div>
          <div class="col-md-12">
            <?= renderInput('DOMICILIO FISCAL:', 'domicilioFiscal', 'text', '', true); ?>
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
  despachosMain.classList.add('active')
  clientesMain.classList.add('active')
  clientesReg.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL CLIENTE SERA REGISTRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/clientes/clientesRegistrar.php')
      }
    })
  })
</script>