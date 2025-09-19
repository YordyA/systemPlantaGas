<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>DONACIONES REALIZADAS</h2>
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
                  <label class="form-label text-dark text-bold">DEL</label>
                  <input type="date" id="del" class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark text-bold">HASTA</label>
                  <input type="date" id="hasta" class="form-control form-control-lg">
                </div>
              </div>
            </div>
            <div class="table-wrapper table-responsive">
              <table class="table text-center" id="Tabla">
                <thead>
                  <tr>
                    <th class="text-center">
                      <h6>TIPO</h6>
                    </th>
                    <th class="text-center">
                      <h6>FECHA</h6>
                    </th>
                    <th class="text-center">
                      <h6>N DONACION <br> (SISTEMA)</h6>
                    </th>
                    <th class="text-center">
                      <h6>RIF / CEDULA</h6>
                    </th>
                    <th class="text-center">
                      <h6>RAZON SOCIAL</h6>
                    </th>
                    <th class="text-center">
                      <h6>MONTO TOTAL EN BS</h6>
                    </th>
                    <th class="text-center">
                      <h6>MONTO TOTAL EN USD</h6>
                    </th>
                    <th class="text-center">
                      <h6>VER DETALLE FACTURA</h6>
                    </th>
                    <th class="text-center">
                      <h6>ANULAR</h6>
                    </th>
                  </tr>
                </thead>
                <tbody id="TablaInformacion"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  facturacionMain.classList.add('active')
  venderFacturasDonaciones.classList.add('active')

  $("#del").val(fechaHoy())
  $("#hasta").val(fechaHoy())

  const enviarDatosGet = async (url) => {
    const respuesta = await penticionAjaxGET(url)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      data_table.destroy()
      listado()
    }
  }

  const listado = async () => {
    await ListaInformacion(['modulos/reportes/ReporteFacturasDonaciones.php?del=' + del.value +
      '&hasta=' + hasta
      .value
    ])
  }

  del.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    listado()
  })

  hasta.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    listado()
  })


  $(document).on('click', '.btnEliminar', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA NOTA SERA ANULADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosGet('modulos/cobrar/cobrarAnularDonaciones.php?id=' + this.value)
      }
    })
  })

  listado()
</script>