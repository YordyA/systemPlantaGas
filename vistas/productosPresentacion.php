<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>PRESENTACIONES DE SALIDA: GAS LICUADO DE PETROLEO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="table-wrapper table-responsive">
              <table class="table text-center" id="tablaMain">
                <thead>
                  <tr>
                    <th class="text-center">
                      <h6>CODIGO</h6>
                    </th>
                    <th class="text-center">
                      <h6>TIPO</h6>
                    </th>
                    <th class="text-center">
                      <h6>DESCRIPCION</h6>
                    </th>
                    <th class="text-center">
                      <h6>CAPACIDAD KG</h6>
                    </th>
                    <th class="text-center">
                      <h6>PRECIO DE VENTA</h6>
                    </th>
                    <th class="text-center">
                      <h6>EDITAR</h6>
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
  productosPresentacion.classList.add('active')
  const url = ['modulos/productos/productosPresentaciones.php', []]
  ajaxListaInformacionGET(url)
</script>