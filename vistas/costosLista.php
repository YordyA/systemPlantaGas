<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>LISTA DE CONCEPTOS DE COSTOS</h2>
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
                      <h6>DESCRIPCION</h6>
                    </th>
                    <th class="text-center">
                      <h6>COSTO</h6>
                    </th>
                    <th class="text-center">
                      <h6>ACTUALIZAR</h6>
                    </th>
                    <th class="text-center">
                      <h6>ELIMINAR</h6>
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
  produccionMain.classList.add('active')
  produccionReportMain.classList.add('active')
  costosLista.classList.add('active')

  const url = ['modulos/inventarioProduccion/inventarioCostosLista.php', []]
  $(document).on('click', '.btnEliminar', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL ARTICULO SERA ELIMINADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionGET('modulos/articulos/articulosEliminar.php?id=' + this.value)
      }
    })
  })

  ajaxListaInformacionGET(url)
</script>