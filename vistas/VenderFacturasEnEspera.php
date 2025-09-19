<?php if ($_SESSION['PlantaGas']['PesajeInicial'] == 0) {
  echo '
      <section class="table-components">
        <div class="container-fluid">
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="title mb-30">
                </div>
              </div>
            </div>
          </div>
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h1 class="text-center text-bold text-danger">FALTA EL PESAJE INICIAL PARA FACTURAR</h1>
                  <h2 class="text-bold text-primary text-center">
                    <div class="d-flex justify-content-center">
                      <img src="logo.svg" width="450px">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  ';
  echo "<script>
        facturacionMain.classList.add('active')
        facturaEnEspera.classList.add('active')
      </script>";
  exit();
} ?>

<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURAS EN ESPERA</h2>
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
                      <h6>N FACTURA EN ESPERA <br> (SISTEMA)</h6>
                    </th>
                    <th class="text-center">
                      <h6>RIF</h6>
                    </th>
                    <th class="text-center">
                      <h6>RAZÓN SOCIAL</h6>
                    </th>
                    <th class="text-center">
                      <h6>VER DETALLE FACTURA</h6>
                    </th>
                    <th class="text-center">
                      <h6>EMITIR CUENTA POR COBRAR</h6>
                    </th>
                    <th class="text-center">
                      <h6>EMITIR DONACIÓN</h6>
                    </th>
                    <th class="text-center">
                      <h6>AUTO CONSUMO</h6>
                    </th>
                    <th class="text-center">
                      <h6>DONACIÓN TRABAJADOR</h6>
                    </th>
                    <th class="text-center">
                      <h6>ENTREGA DE PRODUCTOS</h6>
                    </th>
                    <th class="text-center">
                      <h6>QUITAR</h6>
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

<div class="modal fade" id="modalFactura" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
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
                <h4>Nro Factura en Espera</h4>
              </th>
              <th class="text-center">
                <h4>RIF</h4>
              </th>
              <th class="text-center">
                <h4>RAZÓN SOCIAL</h4>
              </th>
              <th class="text-center">
                <h4>ESTADO</h4>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="NroVenta"></td>
              <td id="Rif"></td>
              <td id="Nombre"></td>
              <td><span class="status-btn warning-btn">EN ESPERA</span></td>
            </tr>
          </tbody>
        </table>
        <br>
        <table class="table text-center" id="Tabla">
          <thead>
            <tr>
              <th class="text-center">
                <h5>CODIGO</h5>
              </th>
              <th class="text-center">
                <h5>ARTICULO</h5>
              </th>
              <th class="text-center">
                <h5>CANTIDAD</h5>
              </th>
              <th class="text-center">
                <h5>P/U</h5>
              </th>
              <th class="text-center">
                <h5>TOTAL</h5>
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
  facturaEnEspera.classList.add('active')

  const listado = async () => {
    const peticion = await fetch('modulos/reportes/ReporteFacturasEnEsperaGerente.php')
    const respuesta = await peticion.json()
    TablaInformacion.innerHTML = respuesta
    data_table = $('#Tabla').DataTable({
      pageLength: 20,
      destroy: true,
      lengthChange: true,
      lengthMenu: [20, 30, 50],
      order: [
        [0, "desc"]
      ],
      columnDefs: [{
        orderable: false,
        targets: [3, 4, 5, 6],
      }],
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

  const enviarDatosFacturaGet = async (url) => {
    const peticion = await fetch(url)
    const respuesta = await peticion.json()
    console.log(respuesta)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      data_table.destroy()
      listado()
      if (respuesta.url !== '') {
        window.open(respuesta.url)
      }
    }
  }

  const factura = async (id) => {
    const peticion = await fetch('modulos/reportes/VisualizarFacturasEnEspera.php?id=' + id)
    const respuesta = await peticion.json()
    NroVenta.innerHTML = respuesta.NroVenta
    Rif.innerHTML = respuesta.Rif
    Nombre.innerHTML = respuesta.Nombre
    tabla.innerHTML = respuesta.tabla
    $("#modalFactura").modal("show")
  }

  const quitarFactura = async (id) => {
    const peticion = await fetch('modulos/facturacion/FacturacionEliminarFacturaEnEspera.php?id=' + id)
    const respuesta = await peticion.json()
    data_table.destroy()
    listado()
    alertas(respuesta)
  }

  const donacionFactura = async (id) => {
    const peticion = await fetch('modulos/cobrar/CobrarRegistrarDonacion.php?id=' + id + '&d=0')
    const respuesta = await peticion.json()
    console.log(respuesta)
    if (respuesta[0]) {
      data_table.destroy()
      listado()
      Swal.fire({
        title: '!DONACION REALIZADA¡',
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR'
      }).then((result) => {
        if (result.isConfirmed) {
          window.open('modulos/pdf/Donacion.php?id=' + respuesta[1] + '&tc=' + respuesta[2])
        }
      })
    } else {
      Swal.fire({
        title: "¡EXISTENCIA INSUFICIENTE!",
        text: respuesta[1],
        icon: "error",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ACEPTAR"
      })
    }
  }

  const autoConsumo = async (id) => {
    const peticion = await fetch('modulos/cobrar/CobrarRegistrarDonacion.php?id=' + id + '&d=1')
    const respuesta = await peticion.json()
    if (respuesta[0]) {
      data_table.destroy()
      listado()
      Swal.fire({
        title: '!AUTO CONSUMO RETIRADO¡',
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR'
      }).then((result) => {
        if (result.isConfirmed) {
          window.open('modulos/pdf/Donacion.php?id=' + respuesta[1] + '&tc=' + respuesta[2])
        }
      })
    } else {
      Swal.fire({
        title: "¡EXISTENCIA INSUFICIENTE!",
        text: respuesta[1],
        icon: "error",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ACEPTAR"
      })
    }
  }

  const donacionTrabajador = async (id) => {
    const peticion = await fetch('modulos/cobrar/CobrarRegistrarDonacion.php?id=' + id + '&d=2')
    const respuesta = await peticion.json()
    if (respuesta[0]) {
      data_table.destroy()
      listado()
      Swal.fire({
        title: '!DONACIÓN TRABAJADOR REGISTRADO',
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR'
      }).then((result) => {
        if (result.isConfirmed) {
          window.open('modulos/pdf/Donacion.php?id=' + respuesta[1] + '&tc=' + respuesta[2])
        }
      })
    } else {
      Swal.fire({
        title: "¡EXISTENCIA INSUFICIENTE!",
        text: respuesta[1],
        icon: "error",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ACEPTAR"
      })
    }
  }

  $(document).on('click', '.factura', function() {
    factura(this.value)
  })

  $(document).on('click', '.cuentaPorCobrar', function() {
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'LA CxC SERA EMITIDA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'http://localhost/MaquinaFiscal/index.php?id=' + this.value
      }
    })
  })

  $(document).on('click', '.btnEntregaProductos', function() {
    Swal.fire({
      title: '¿ESTA SEGRUO?',
      text: 'LOS PRODUCTOS SERAN ENTREGADOS',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosFacturaGet('modulos/cobrar/cobrarRegistrarEntregaProductos.php?id=' + this.value)
      }
    })
  })

  $(document).on('click', '.donacion', function() {
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'La donación sera registrada',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        donacionFactura(this.value)
      }
    })
  })

  $(document).on('click', '.consumo', function() {
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'EL CONSUMO SERA RETIRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        autoConsumo(this.value)
      }
    })
  })

  $(document).on('click', '.btnDonacionTrabajador', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA DONACIÓN AL TRABAJADOR SERA RETIRADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        donacionTrabajador(this.value)
      }
    })
  })

  $(document).on('click', '.quitar', function() {
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'La factura sera eliminada de la lista de espera',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        quitarFactura(this.value)
      }
    })
  })

  listado()
</script>