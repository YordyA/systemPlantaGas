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
                      <h6>ESTADO</h6>
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
  // Función para imprimir con MikePOS
  function imprimirConMikePOS(ticketData) {
    try {
      // Crear instancia de MikePOS
      const printer = new MikePOS.Printer();

      // Configurar impresora térmica
      printer.setPrinter('thermal');

      // Encabezado - Nombre de la empresa
      printer.align('center');
      printer.bold(true);
      printer.text(ticketData.empresa);
      printer.bold(false);
      printer.newLine();
      printer.newLine();

      // Items de la venta
      printer.align('left');
      ticketData.items.forEach(item => {
        printer.text(`${item.cantidad} x ${item.descripcion}`);
        printer.newLine();
      });

      printer.newLine();

      // Número de venta
      printer.align('center');
      printer.text(`Venta #${ticketData.nro_venta}`);
      printer.newLine();

      // Fecha y hora
      const ahora = new Date();
      printer.text(ahora.toLocaleDateString() + ' ' + ahora.toLocaleTimeString());
      printer.newLine();
      printer.newLine();

      // Cortar papel
      printer.cut();

      // Imprimir
      printer.print();

      console.log('Ticket impreso con éxito');
    } catch (error) {
      console.error('Error al imprimir con MikePOS:', error);
      // Fallback: imprimir en consola para debug
      console.log('=== TICKET DE DESPACHO ===');
      console.log(ticketData.empresa);
      console.log('--------------------------');
      ticketData.items.forEach(item => {
        console.log(`${item.cantidad} x ${item.descripcion}`);
      });
      console.log('--------------------------');
      console.log(`Venta #${ticketData.nro_venta}`);
    }
  }

  // Modificar la función alertas para imprimir automáticamente
  const alertas = alerta => {
    if (alerta.alerta === 'simple') {
      Swal.fire({
        icon: alerta.tipo,
        title: alerta.titulo,
        text: alerta.texto,
      });
    } else if (alerta.alerta === 'actualizacion') {
      // Imprimir ticket automáticamente si hay datos
      if (alerta.ticket_data) {
        imprimirConMikePOS(alerta.ticket_data);
      }

      // Mostrar mensaje de éxito
      Swal.fire({
        icon: alerta.tipo,
        title: alerta.titulo,
        text: alerta.texto,
        timer: 2000,
        showConfirmButton: false
      });

      // Recargar la tabla después de un breve delay
      setTimeout(() => {
        if (typeof data_table !== 'undefined' && data_table) {
          data_table.destroy();
        }
        ListaInformacion(['modulos/reportes/ReporteFacturasEmitidas.php?del=' +
          document.getElementById('del').value + '&hasta=' +
          document.getElementById('hasta').value
        ]);
      }, 1000);
    }
    // ... otros casos de alerta
  };

  // Manejar clic en botones de despacho
  $(document).on('click', '.despachar-btn', function() {
    const idResumenVenta = $(this).val();

    Swal.fire({
      title: '¿REGISTRAR DESPACHO?',
      text: 'Se registrará el despacho de esta venta',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        // Hacer petición AJAX para registrar despacho
        fetch('modulos/inventario/inventarioRegistrarDespacho.php?id=' + idResumenVenta)
          .then(response => response.json())
          .then(data => {
            // Mostrar alerta según la respuesta
            alertas(data);
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Ocurrió un error al procesar la solicitud'
            });
          });
      }
    });
  });

  // Tu función ListaInformacion existente
  const ListaInformacion = async (info) => {
    try {
      const peticion = await fetch(info[0]);
      const respuesta = await peticion.json();
      document.getElementById('TablaInformacion').innerHTML = respuesta;

      // Inicializar DataTable si es necesario
      if ($.fn.DataTable) {
        data_table = $("#Tabla").DataTable({
          // tus configuraciones de DataTable
        });
      }
    } catch (error) {
      console.error('Error al cargar información:', error);
    }
  };

  ListaInformacion(['modulos/reportes/ReporteFacturasEmitidas.php?del=' + del.value + '&hasta=' + hasta.value])
</script>