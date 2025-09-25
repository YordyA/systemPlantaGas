<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURAS EMITIDAS POR DESPACHAR</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
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
                      <h6>DESPACHAR</h6>
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
  inventarioPlantaMain.classList.add('active')
  VenderFacturasEmitidasPorDespachar.classList.add('active')

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

  const despacharFactura = async (id) => {
    // Confirmar antes de despachar
    Swal.fire({
      title: '¿Confirmar despacho?',
      text: "Esta acción descontará del inventario y generará el ticket",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, despachar',
      cancelButtonText: 'Cancelar'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const peticion = await fetch('modulos/inventario/inventarioRegistrarDespacho.php?id=' + id)
          const respuesta = await peticion.json()
          
          if (respuesta.alerta === "actualizacion") {
            // Mostrar éxito
            Swal.fire({
              title: respuesta.titulo,
              text: respuesta.texto,
              icon: 'success',
              confirmButtonText: 'Aceptar'
            }).then(() => {
              // Recargar la tabla
              ListaInformacion(['modulos/reportes/ReporteFacturasEmitidasPendientes.php'])
              
              // Si hay datos para ticket, imprimir
              if (respuesta.ticket_data) {
                imprimirTicket(respuesta.ticket_data)
              }
            })
          } else {
            // Mostrar error
            Swal.fire({
              title: respuesta.titulo,
              text: respuesta.texto,
              icon: 'error',
              confirmButtonText: 'Aceptar'
            })
          }
        } catch (error) {
          Swal.fire({
            title: 'Error',
            text: 'Ocurrió un error al procesar el despacho',
            icon: 'error',
            confirmButtonText: 'Aceptar'
          })
        }
      }
    })
  }

  // Función para imprimir ticket (debes adaptarla a tu sistema de tickets)
  const imprimirTicket = (ticketData) => {
    // Aquí va tu lógica para imprimir el ticket
    console.log('Datos del ticket:', ticketData)
    
    // Ejemplo básico de impresión
    const ventanaTicket = window.open('', '_blank')
    ventanaTicket.document.write(`
      <html>
        <head>
          <title>Ticket de Despacho</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .ticket { width: 300px; }
            .header { text-align: center; margin-bottom: 20px; }
            .item { display: flex; justify-content: space-between; margin: 5px 0; }
            .total { border-top: 1px solid #000; margin-top: 10px; padding-top: 10px; font-weight: bold; }
          </style>
        </head>
        <body>
          <div class="ticket">
            <div class="header">
              <h3>${ticketData.empresa}</h3>
              <p>DESPACHO DE GAS</p>
              <p>N° Venta: ${ticketData.nro_venta}</p>
            </div>
            <div class="items">
              ${ticketData.items.map(item => `
                <div class="item">
                  <span>${item.descripcion}</span>
                  <span>${item.cantidad}</span>
                </div>
              `).join('')}
            </div>
            <div class="total">
              <span>Total Despachado:</span>
              <span>${ticketData.total} kg</span>
            </div>
          </div>
        </body>
      </html>
    `)
    ventanaTicket.document.close()
    ventanaTicket.print()
    ventanaTicket.close()
  }

  // Event listeners
  $(document).on('click', '.factura', function() {
    factura(this.value)
  });

  $(document).on('click', '.despachar-btn', function() {
    despacharFactura(this.value)
  });

  ListaInformacion(['modulos/reportes/ReporteFacturasEmitidasPendientes.php'])
</script>