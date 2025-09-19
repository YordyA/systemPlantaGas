<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>INVENTARIO DE PLANTA</h2>
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
                      <h6>DESCRIPCIÓN</h6>
                    </th>
                    <th class="text-center">
                      <h6>EXISTENCIA</h6>
                    </th>
                    <th class="text-center">
                      <h6>RETIRAR</h6>
                    </th>
                    <th class="text-center">
                      <h6>EDITAR</h6>
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
<div class="modal fade" id="modalRetirar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">RETIRAR EXISTENCIA</h5>
      </div>
      <form id="formRetirar" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('CANTIDAD A RETIRAR:', 'cant', 'number', '', true, 3, 'step="0.0000001"'); ?>
          <?= renderInput('CONCEPTO DEL RETIRO:', 'observacion', 'textarea', '', true, 3); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control form-control-lg">RETIRAR</button>
          <button type="reset" class="btn btn-danger form-control form-control-lg"
            data-bs-dismiss="modal">CERRAR</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  inventarioPlantaMain.classList.add('active')
  inventarioPlantaList.classList.add('active')

  const peticionPostActualizar = async (url, form, modal) => {
    const respuesta = await peticionAjaxPOST(url, form)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      form.reset()
      $(modal).modal('hide')
    }
  }

  const url = ['modulos/inventario/inventarioPlantaLista.php', []]

  $(document).on('click', '.btnEliminar', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PRODUCTO SERA ELIMINADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionGET('modulos/inventarioPlanta/inventarioPlantaEliminar.php?id=' + this.value)
      }
    })
  })

  $(document).on('click', '.btnPrecio', function() {
    IDTemporal = this.value
    $('#modalPrecio').modal('show')
    $('#modalPrecio').on('shown.bs.modal', function() {
      formPrecio.precioVenta.focus()
    })
  })

  $(document).on('click', '.btnRetirar', function() {
    IDTemporal = this.value
    $('#modalRetirar').modal('show')
    $('#modalRetirar').on('shown.bs.modal', function() {
      formRetirar.cant.focus()
    })
  })

  formRetirar.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA CANTIDAD SERA INGRESADA SERA RETIRADA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionPostActualizar('modulos/inventario/inventarioRetirar.php?id=' +
          IDTemporal, formRetirar, '#modalRetirar')
      }
    })
  })

  ajaxListaInformacionGET(url)
</script>