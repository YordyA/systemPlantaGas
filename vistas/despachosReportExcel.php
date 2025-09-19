<?php
require_once './modulos/main.php';
require_once './modulos/despachos/despachosMain.php';
?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REPORTES EXCEL</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row">
              <div class="col-md-4">
                <?= renderInput('DEL:', '', 'date', '', true, 3, 'id="d"'); ?>
              </div>
              <div class="col-md-4">
                <?= renderInput('HASTA:', '', 'date', '', true, 3, 'id="h"'); ?>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">TIPO DESPACHO</label>
                  <select class="form-select form-select-lg text-bold" id="IDTipoDesp" required>
                    <?php foreach (despachosTiposLista() as $row) : ?>
                      <option value="<?= encriptar($row['IDTipoDespacho']); ?>"><?= $row['DescripcionTipoDesp']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <hr>
              <div class="col-md-12">
                <div class="mb-3">
                  <button class="btn btn-warning form-control" id="btnExportReportDespXCliente">EXPORTAR REPORTE
                    DISTRIBUCION DESPACHO X CLIENTE</button>
                </div>
              </div>
              <hr>
              <div class="col-md-12">
                <div class="mb-3">
                  <button class="btn btn-warning form-control" id="btnExportReportDistDesp">EXPORTAR REPORTE
                    DISTRIBUCION DESPACHOS</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  despachosMain.classList.add('active')
  despachosReportMain.classList.add('active')
  despachosReportExcel.classList.add('active')

  d.value = fechaHoy()
  h.value = fechaHoy()

  btnExportReportDespXCliente.addEventListener('click', async () => {
    window.open('modulos/excel/EXCELDespachoXCliente.php?d=' + d.value + '&h=' + h.value + '&IDTipoDesp=' +
      IDTipoDesp.value, '_blank')
  })

  btnExportReportDistDesp.addEventListener('click', async () => {
    window.open('modulos/excel/EXECELDistribucionXDespacho.php?d=' + d.value + '&h=' + h.value + '&IDTipoDesp=' +
      IDTipoDesp.value, '_blank')
  })
</script>