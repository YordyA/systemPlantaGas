<?php require_once 'modulos/main.php'; ?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>MOVIMIENTO INVENTARIO DE PLANTA ENTRE FECHAS</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">DEL:</label>
                  <input type="date" id="del" class="form-control form-control-lg" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">HASTA:</label>
                  <input type="date" id="hasta" class="form-control form-control-lg" required>
                </div>
              </div>
            </div>
            <div class="table-wrapper table-responsive">
              <table class="table text-center" id="tablaMain">
                <thead>
                  <tr>
                    <th class="text-center">
                      <h6>DESCRIPCION</h6>
                    </th>
                    <th class="text-center">
                      <h6>INV INICIAL</h6>
                    </th>
                    <th class="text-center">
                      <h6>ENTRADAS</h6>
                    </th>
                    <th class="text-center">
                      <h6>SALIDAS</h6>
                    </th>
                    <th class="text-center">
                      <h6>INV FINAL</h6>
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
  inventarioPlantaEntreFechas.classList.add('active')

  del.value = fechaHoy()
  hasta.value = fechaHoy()

  del.addEventListener('change', (e) => {
    dataTable.destroy()
    ajaxListaInformacionGET(
      [
        'modulos/inventario/reportInventarioEntreFechas.php?d=' + del.value + '&h=' + hasta.value, []
      ]
    )
  })

  hasta.addEventListener('change', (e) => {
    dataTable.destroy()
    ajaxListaInformacionGET(
      [
        'modulos/inventario/reportInventarioEntreFechas.php?d=' + del.value + '&h=' + hasta.value, []
      ]
    )
  })

  ajaxListaInformacionGET(
    [
      'modulos/inventario/reportInventarioEntreFechas.php?d=' + del.value + '&h=' + hasta.value, []
    ]
  )
</script>