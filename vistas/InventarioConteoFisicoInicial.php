<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>APERTURA DE INVENTARIO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-3">
        <div class="row mb-3">
          <div class="col-md-4">
            <button id="btn_iniciar" class="btn btn-warning form-control">INICIAR</button>
          </div>
          <div class="col-md-4">
            <button id="btn_terminar" class="btn btn-success form-control">COMPLETAR</button>
          </div>
          <div class="col-md-4">
            <button id="btn_cancelar" class="btn btn-danger form-control">CANCELAR</button>
          </div>
        </div>
        <hr>
        <div class="row">
          <table class="table table-sm text-center">
            <thead class="">
              <tr>
                <th>ALMACEN</th>
                <th>UNIDAD DE MEDIDA</th>
                <th>EXISTENCIA EN SISTEMA</th>
                <th>EXISTENCIA FISICA</th>
                <th>DIFERENCIA</th>
              </tr>
            </thead>
            <tbody id="tabla_temporal_ventas"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal_modificar_cantidad" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Ingresando Cantidad Fisica</h5>
      </div>
      <div class="modal-body">
        <form id="actualizar_cantidad">
          <div>
            <label class="form-label text-dark">Cantidad</label>
            <input type="number" name="nueva_cantidad" class="form-control form-control-lg" step="0.001" required>
          </div>
      </div>
      <div class="modal-footer flex-nowrap">
        <button type="submit" class="btn btn-primary form-control">INSERTAR</button>
        <button type="reset" class="btn btn-danger form-control" data-bs-dismiss="modal">CERRAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  inventarioPlantaMain.classList.add('active')
  InventarioConteoFisicoInicial.classList.add('active')

  const formulario_actualizar_cantidad = document.getElementById('actualizar_cantidad')

  let id_temporal

  const iniciarConteo = async () => {
    const peticion = await fetch('modulos/conteoFisico/inventarioConteoFisicoInicial.php')
    const respuesta = await peticion.json()
    listado()
    return alertas(respuesta)
  }

  const listado = async () => {
    const peticion = await fetch('modulos/conteoFisico/inventarioConteoFisicoListaInicial.php')
    const respuesta = await peticion.json()
    tabla_temporal_ventas.innerHTML = respuesta.tabla
  }


  const cambiar_cantidad = async (id, cantidad) => {
    const peticion = await fetch('modulos/conteoFisico/inventarioConteoFisicoDiferenciaInicial.php?i=' + id +
      '&cantidad=' + cantidad)
    const respuesta = await peticion.json()
    console.log(respuesta)
    $("#modal_modificar_cantidad").modal('hide')
    formulario_actualizar_cantidad.reset()
    if (respuesta == true) {
      listado()
    }
  }

  const terminarConteoFisico = async () => {
    const peticion = await fetch('modulos/conteoFisico/inventarioConteoFisicoCompletarInicial.php')
    const respuesta = await peticion.json()
    console.log(respuesta)
    if (respuesta[0]) {
      Swal.fire({
        title: '!APERTURA REALIZADA¡',
        text: 'La Apertura Fue Realizada con Exito',
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR',
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'inventarioPlantaLista'
        }
      })
    } else {
      Swal.fire({
        title: '!APERTURA NO REALIZADA¡',
        text: 'La existencia fisica no puede ser igual a la existencia en sistema',
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR',
      })
    }
  }

  const cancelar_despacho = async () => {
    const peticion = await fetch('modulos/conteoFisico/inventarioConteoFisicoCancelarInicial.php')
    const respuesta = await peticion.json()
    if (respuesta == true) {
      listado()
      Swal.fire({
        title: '!APERTURA CANCELADA¡',
        text: 'El Conteo Fue Cancelado',
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'ACEPTAR'
      })
    }
  }


  $(document).on('click', '.actualizar_cantidad', function() {
    id_temporal = this.value
    $("#modal_modificar_cantidad").modal('show')
    $("#modal_modificar_cantidad").on("shown.bs.modal", function() {
      formulario_actualizar_cantidad.nueva_cantidad.focus();
    });
  });


  formulario_actualizar_cantidad.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'Ingresando Pejase Fisico',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        cambiar_cantidad(id_temporal, formulario_actualizar_cantidad.nueva_cantidad.value)
      }
    })
  })


  btn_cancelar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'La Apertura Sera Cancelada',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        cancelar_despacho()
      }
    })
  })


  btn_iniciar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'La Apertura sera iniciada',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        iniciarConteo()
      }
    })
  })

  btn_terminar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿Esta Seguro?',
      text: 'La Apertura sera registrada',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        terminarConteoFisico()
      }
    })
  })

  listado()
</script>