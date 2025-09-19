<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>ENTREGA DE PRODUCTOS</h2>
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
                  <input type="date" id="d" class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">HASTA:</label>
                  <input type="date" id="h" class="form-control form-control-lg">
                </div>
              </div>
              <hr>
            </div>
            <div class="table-wrapper table-responsive">
              <table class="table text-center" id="Tabla">
                <thead>
                  <tr>
                    <th class="text-center">
                      <h6>FECHA DE ENTREGA</h6>
                    </th>
                    <th class="text-center">
                      <h6>N FACTURA <br> (SISTEMA)</h6>
                    </th>
                    <th class="text-center">
                      <h6>CAJA</h6>
                    </th>
                    <th class="text-center">
                      <h6>RIF / CEDULA</h6>
                    </th>
                    <th class="text-center">
                      <h6>RAZON SOCIAL</h6>
                    </th>
                    <th class="text-center">
                      <h6>VER DETALLE FACTURA</h6>
                    </th>
                    <th class="text-center">
                      <h6>FACTURAR</h6>
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
  VenderFacturasEntregaProductos.classList.add('active')

  d.value = fechaHoy()
  h.value = fechaHoy()

  const listaTempInfo = async () => {
    await ListaInformacion(['modulos/reportes/reportCobrarListaEntregaProductos.php?d=' + d.value + '&h=' + h.value])
  }

  const enviarDatosGet = async (url) => {
    const respuesta = await penticionAjaxGET(url)
    alertas(respuesta)
    console.log(respuesta)
    if (respuesta.tipo === 'success') {
      data_table.destroy()
      listaTempInfo()
    }
  }

  d.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    listaTempInfo()
  })

  h.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    listaTempInfo()
  })

  $(document).on('click', '.btnEliminar', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA ENTREGA DE PRODUCTOS SERA ANULADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosGet('modulos/cobrar/cobrarAnularEntregaProductos.php?id=' + this.value)
      }
    })
  })

  listaTempInfo()
</script>