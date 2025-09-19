<?php
if (!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['nr']) || empty($_GET['nr']) || !isset($_GET['fecha']) || empty($_GET['fecha'])) {
  exit('<script>window.history.back()</script>');
}
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ANULAR DE DESPACHO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <form id="formulario">
              <div class="row">
                <div class="mb-3">
                  <label class="form-label text-dark">INGRESE SU CONTRASEÑA PARA CONFIRMAR LA ANULACION:</label>
                  <input type="password" class="form-control form-control-lg" name="clave" required>
                </div>
              </div>
              <div class="text-center">
                <button class="main-btn primary-btn btn-hover m-1">
                  <strong>CONFIRMAR</strong>
                </button>
                <button type="reset" class="main-btn danger-btn btn-hover m-1">
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
despachoPlantaMain.classList.add('active')

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
      enviarInformacion(
        'modulos/despachoPlanta/despachoPlantaAnular.php?id=<?php echo $_GET['id']; ?>&nr=<?php echo $_GET['nr']; ?>&fecha=<?php echo $_GET['fecha']; ?>'
      )
    }
  })
})
</script>