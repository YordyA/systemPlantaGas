<?php require_once './modulos/dependencias.php';
require_once './modulos/sessionStart.php'; ?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR CISTERNA</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <form id="formulario" class="row" autocomplete="off">
          <div class="col-md-4">
            <?= renderInput('Nro de Identificacion (MODELO):', 'modelo', 'text', '', true); ?>
          </div>
          <div class="col-md-4">
            <?= renderInput('Capacidad (LITROS):', 'capacidad', 'number', '', true, 'step="0.01"'); ?>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-dark">Tipo</label>
              <select class="form-select form-select-lg text-bold" name="tipo" id="tipo" required>
                <option value="" selected>SELECCIONE</option>
                <option value="<?php echo encriptar(1); ?>">Propia</option>
                <option value="<?php echo encriptar(2); ?>">Privada</option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label class="form-label text-dark">Propietario</label>
              <input type="text" class="form-control form-control-lg" name="propietario" required>
            </div>
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
  flotaMain.classList.add('active')
  cisternaRegistrar.classList.add('active')

  document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const propietarioInput = document.querySelector('input[name="propietario"]');
    tipoSelect.addEventListener('change', function() {
      if (this.value === "<?php echo encriptar(2); ?>") { // Si es "Privada"
        propietarioInput.disabled = false;
        propietarioInput.value = ""; // Limpiar el campo
      } else if (this.value === "<?php echo encriptar(1); ?>") { // Si es "Propia"
        propietarioInput.disabled = false;
        propietarioInput.value = "<?php echo RAZONSOCIAL ?>"; // Asignar valor 1
      }
    });
  });


  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA CISTERNA SERA REGISTRADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/cisternas/cisternasRegistrar.php')
      }
    })
  })
</script>