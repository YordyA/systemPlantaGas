<?php require_once 'modulos/main.php' ?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>MAQUINA FISCAL</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="mb-3">
                  <button class="col-md-12 btn btn-warning" id="reporteZ">REALIZAR REPORTE Z</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>LIBRO DE VENTA REPORTE Z</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <form id="formReporteZ">
              <div class="row mb-3">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label text-dark">TIPO DE ARCHIVO</label>
                    <select class="form-select form-select-lg text-bold" name="tipoArchivo" required>
                      <option selected value="">SELECCIONE</option>
                      <option value="<?= Encriptar('excel'); ?>">LIBRO EN EXCEL</option>
                      <option value="<?= Encriptar('text'); ?>">ARCHIVO TXT ()</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <?= renderInput('DEL:', 'd', 'date', '', true); ?>
                </div>
                <div class="col-md-4">
                  <?= renderInput('HASTA:', 'h', 'date', '', true); ?>
                </div>
                <div class="col-md-12">
                  <div class="mb-3">
                    <button type="submit" class="btn btn-warning form-control">
                      EXPORTAR
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  facturacionMain.classList.add('active')
  MaquinaFIscalReporteZ.classList.add('active')

  formReporteZ.d.value = fechaHoy()
  formReporteZ.h.value = fechaHoy()

  reporteZ.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL REPORTE Z SERA EMITIDO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'http://localhost/MaquinaFiscal/ReporteZ.php'
      }
    })
  })

  formReporteZ.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL REPROTE SERA EMITIDO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        window.open('modulos/reportesExcel/EXCELReportesZ.php?d=' + formReporteZ.d.value + '&h=' + formReporteZ.h
          .value + '&tipoArchivo=' + formReporteZ.tipoArchivo.value, '_blank')
      }
    })
  })
</script>