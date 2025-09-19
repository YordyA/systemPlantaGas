<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>MOVIMIENTO INVENTARIO PLANTA</h2>
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
                  <label class="form-label text-dark">TIPO MOVIMIENTO:</label>
                  <select class="form-select form-select-lg text-bold" id="tipoMov" required>
                    <option selected value="">SELECCIONE</option>
                    <option value="<?= encriptar(1); ?>">ENTRADAS</option>
                    <option value="<?= encriptar(2); ?>">SALIDAS</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="table-wrapper table-responsive">
              <table class="table text-center" id="tablaMain">
                <thead>
                  <tr>
                    <th class="text-center">
                      <h6>TIPO MOVIMIENTO</h6>
                    </th>
                    <th class="text-center">
                      <h6>FECHA MOVIMIENTO</h6>
                    <th class="text-center">
                      <h6>DESCRIPCION</h6>
                    </th>
                    <th class="text-center">
                      <h6>EXISTENCIA ANTERIOR</h6>
                    </th>
                    <th class="text-center">
                      <h6>MOVIMIENTO</h6>
                    </th>
                    <th class="text-center">
                      <h6>EXISTENCIA ACTUAL</h6>
                    </th>
                    <th class="text-center">
                      <h6>OBSERVACION</h6>
                    </th>
                    <th class="text-center">
                      <h6>RESPONSABLE</h6>
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
</section>

<script>
inventarioPlantaMain.classList.add('active')
inventarioPlantaReportMain.classList.add('active')
inventarioPlantaReportMov.classList.add('active')

const tablaTemp = async () => {
  await ajaxListaInformacionGET(
    [
      'modulos/inventario/reportInventariMovimiento.php?d=' + d.value + '&h=' + h
      .value + '&id=' + tipoMov.value
    ]
  )
}

d.value = fechaHoy()
h.value = fechaHoy()

d.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaTemp()
})

h.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaTemp()
})

tipoMov.addEventListener('change', (e) => {
  dataTable.destroy()
  tablaTemp()
})

tablaTemp()
</script>