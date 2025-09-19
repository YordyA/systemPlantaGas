<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURAS EMITIDAS</h2>
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
                      <h6>N FACTURA <br> (SISTEMA)</h6>
                    </th>
                    <th class="text-center">
                      <h6>N FACTURA <br>(FISCAL)</h6>
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
                      <h6>ESTADO</h6>
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

<div class="modal fade" id="modalFactura" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modalTitleId">FACTURA</h3>
      </div>
      <div class="modal-body">
        <h4 class="modal-title" id="modalTitleId">DATOS DEL CLIENTE</h4>
        <table class="table text-center" id="Tabla">
          <thead>
            <tr>
              <th class="text-center">
                <h4>NRO VENTA</h4>
              </th>
              <th class="text-center">
                <h4>NRO FACTU</h4>
              </th>
              <th class="text-center">
                <h4>Rif</h4>
              </th>
              <th class="text-center">
                <h4>Razon Social</h4>
              </th>
              <th class="text-center">
                <h4>ESTATUS</h4>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="NroVenta"></td>
              <td id="NroFactura"></td>
              <td id="Rif"></td>
              <td id="Nombre"></td>
              <td id="Estatus"></td>
            </tr>
          </tbody>
        </table>
        <br>
        <table class="table text-center" id="Tabla">
          <thead>
            <tr>
              <th class="text-center">
                <h5>Codigo</h5>
              </th>
              <th class="text-center">
                <h5>Articulo</h5>
              </th>
              <th class="text-center">
                <h5>Catidad</h5>
              </th>
              <th class="text-center">
                <h5>Precio U</h5>
              </th>
              <th class="text-center">
                <h5>Total</h5>
              </th>
            </tr>
          </thead>
          <tbody id="tabla"></tbody>
        </table>

        <div class="modal-footer flex-nowrap">
          <button type="reset" class="btn btn-danger form-control" data-bs-dismiss="modal">CERRAR</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  facturacionMain.classList.add('active')
  venderFacturasEmitidas.classList.add('active')

  $("#del").val(fecha_hoy())
  $("#hasta").val(fecha_hoy())

  function fecha_hoy() {
    var d = new Date($.now());
    var year = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = mes_temporal < 10 ? "0" + mes_temporal : mes_temporal;
    var dia = d.getDate() < 10 ? "0" + d.getDate() : d.getDate();
    return year + "-" + mes + "-" + dia;
  }

  del.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    ListaInformacion(['modulos/reportes/ReporteFacturasEmitidas.php?del=' + del.value + '&hasta=' + hasta.value])
  })

  hasta.addEventListener('change', (e) => {
    e.preventDefault()
    data_table.destroy()
    ListaInformacion(['modulos/reportes/ReporteFacturasEmitidas.php?del=' + del.value + '&hasta=' + hasta.value])
  })

  const factura = async (id) => {
    const peticion = await fetch('modulos/reportes/VisualizarFacturasEmitida.php?id=' + id)
    const respuesta = await peticion.json()
    NroVenta.innerHTML = respuesta.NroVenta
    NroFactura.innerHTML = respuesta.NroFactura
    Rif.innerHTML = respuesta.Rif
    Nombre.innerHTML = respuesta.Nombre
    Estatus.innerHTML = respuesta.Estatus
    tabla.innerHTML = respuesta.tabla
    $("#modalFactura").modal("show")
  }

  $(document).on('click', '.anular', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA VENTA SERA ANULADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'http://localhost/maquinaFiscal/indexNotaCredito.php?n=' + this.value
      }
    })
  });

  $(document).on('click', '.factura', function() {
    factura(this.value)
  });

  ListaInformacion(['modulos/reportes/ReporteFacturasEmitidas.php?del=' + del.value + '&hasta=' + hasta.value])
</script>