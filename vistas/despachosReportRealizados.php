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
            <h2>DESPACHOS REALIZADOS</h2>
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
                    <option value="" selected>SELECCIONE</option>
                    <?php foreach (despachosTiposLista() as $row) : ?>
                    <option value="<?= encriptar($row['IDTipoDespacho']); ?>"><?= $row['DescripcionTipoDesp']; ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <hr>
              <div class="col-md-12">
                <div class="table-wrapper table-responsive">
                  <table class="table text-center" id="tablaMain">
                    <thead>
                      <tr>
                        <th>
                          <h6 class="text-center">TIPO DESPACHO</h6>
                        </th>
                        <th>
                          <h6 class="text-center">FECHA</h6>
                        </th>
                        <th>
                          <h6 class="text-center">NRO DESPACHO</h6>
                        </th>
                        <th>
                          <h6 class="text-center">R.I.F / CEDULA</h6>
                        </th>
                        <th>
                          <h6 class="text-center">RAZON SOCIAL <br><strong>(CLIENTE)</strong></h6>
                        </th>
                        <th>
                          <h6 class="text-center">SERIE</h6>
                        </th>
                        <th>
                          <h6 class="text-center">NRO FACTURA</h6>
                        </th>
                        <th>
                          <h6 class="text-center">NRO DE CONTROL</h6>
                        </th>
                        <th>
                          <h6 class="text-center">MONTO TOTAL <br><strong>(USD)</strong></h6>
                        </th>
                        <th>
                          <h6 class="text-center">MONTO EXENTO</h6>
                        </th>
                        <th>
                          <h6 class="text-center">BASE IMPONIBLE</h6>
                        </th>
                        <th>
                          <h6 class="text-center">IVA</h6>
                        </th>
                        <th>
                          <h6 class="text-center">MONTO TOTAL</h6>
                        </th>
                        <th>
                          <h6 class="text-center">CHOFER</h6>
                        </th>
                        <th>
                          <h6 class="text-center">CEDULA CHOFER</h6>
                        </th>
                        <th>
                          <h6 class="text-center">OBSERVACION</h6>
                        </th>
                        <th>
                          <h6 class="text-center">RESPONSABLE</h6>
                        </th>
                        <th>
                          <h6 class="text-center">NOTA DE CONTROL</h6>
                        </th>
                        <th>
                          <h6 class="text-center">NOTA DE DESPACHO <br><strong>(USD)</strong></h6>
                        </th>
                        <th>
                          <h6 class="text-center">NOTA DE DESPACHO <br><strong>(BS)</strong></h6>
                        </th>
                        <th>
                          <h6 class="text-center">FACTURAR</h6>
                        </th>
                        <th>
                          <h6 class="text-center">ANULAR</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="tablaInfo"></tbody>
                  </table>
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
despachosReportRealizados.classList.add('active')

const tablaInfoTemp = async () => {
  await ajaxListaInformacionGET(['modulos/despachos/reportDespachoRealizados.php?d=' + d.value + '&h=' + h.value +
    '&id=' + IDTipoDesp.value, [, 17, 18, 19, 20, 21]
  ])
}

d.value = fechaHoy()
h.value = fechaHoy()

d.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaInfoTemp()
})

h.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaInfoTemp()
})

IDTipoDesp.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaInfoTemp()
})

tablaInfoTemp()
</script>