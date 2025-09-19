<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  exit('<script>window.location.href = document.referrer</script>');
}
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ANULAR DESPACHO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <form id="formulario" class="row">
              <div class="col-md-12">
                <?= renderInput('INGRESE SU CONTRASEÑA PARA CONFIRMAR:', 'clave', 'password', '', true); ?>
              </div>
              <div class="text-center">
                <button type="submit" class="main-btn primary-btn btn-hover m-1">
                  <strong>CONFIRMAR</strong>
                </button>
                <button type="reset" class="main-btn danger-btn btn-hover m-1" onclick="volver()">
                  <strong>CANCELAR</strong>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  despachosMain.classList.add('active')
  despachosReportMain.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL DESPACHO SERA ANULADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/despachos/despachosAnular.php?id=<?= $_GET['id']; ?>')
      }
    })
  })
</script>