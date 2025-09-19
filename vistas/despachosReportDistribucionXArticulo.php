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
              <div class="col-md-3">
                <?= renderInput('DEL:', '', 'date', '', true, 3, 'id="d"'); ?>
              </div>
              <div class="col-md-3">
                <?= renderInput('HASTA:', '', 'date', '', true, 3, 'id="h"'); ?>
              </div>
              <div class="col-md-3">
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
              <div class="col-md-3">
                <div class="mb-3">
                  <label class="form-label text-dark">UNIDAD DE MEDIDA</label>
                  <select class="form-select form-select-lg text-bold" id="unidadMedida" required>
                    <option value="<?= encriptar('und'); ?>">UNIDAD</option>
                    <option value="<?= encriptar('kg'); ?>">KILOGRAMOS</option>
                  </select>
                </div>
              </div>
              <hr>
              <div class="col-md-12">
                <div id="tablaReport"></div>
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
  despachosReportDistProducto.classList.add('active')

  const tablaInfoTemp = async () => {
    const respuesta = await peticionAjaxGET('modulos/despachos/reportDespachosDistribucionXArticulo.php?d=' + d
      .value + '&h=' + h.value + '&IDTipoDesp=' + IDTipoDesp.value + '&unidadMedida=' + unidadMedida.value)
    tablaReport.innerHTML = respuesta
    dataTable = new DataTable('#tablaMain', {
      dom: 'Bfrtip',
      buttons: ['copy', 'excel', 'pdf', 'print'],
      pageLength: 120,
      destroy: true,
      language: {
        decimal: '',
        emptyTable: 'No hay información',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ Entradas',
        infoEmpty: 'Mostrando 0 de 0 de 0 Entradas',
        infoFiltered: '(Filtrado de _MAX_ total entradas)',
        infoPostFix: '',
        thousands: ',',
        lengthMenu: 'Mostrar _MENU_ Entradas',
        loadingRecords: 'Cargando...',
        processing: 'Procesando...',
        search: 'Buscar:',
        zeroRecords: 'Sin resultados encontrados',
        paginate: {
          first: 'Primero',
          last: 'Ultimo',
          next: 'Siguiente',
          previous: 'Anterior',
        },
      },
    })
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

  unidadMedida.addEventListener('change', (e) => {
    dataTable.destroy()
    tablaInfoTemp()
  })

  tablaInfoTemp()
</script>