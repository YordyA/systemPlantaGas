<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>INVENTARIO DE MATERIA PRIMA E INSUMOS</h2>
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
                      <h6>TIPO PRODUCTO</h6>
                    </th>
                    <th class="text-center">
                      <h6>CODIGO</h6>
                    </th>
                    <th class="text-center">
                      <h6>DESCRIPCIÓN</h6>
                    </th>
                    <th class="text-center">
                      <h6>ALICUOTA</h6>
                    </th>
                    <th class="text-center">
                      <h6>CAPACIDAD DEL EMPAQUE</h6>
                    </th>
                    <th class="text-center">
                      <h6>UNIDAD DE MEDIDA</h6>
                    </th>
                    <th class="text-center">
                      <h6>COSTO UNITARIO</h6>
                    </th>
                    <th class="text-center">
                      <h6>PRECIO VENTA</h6>
                    </th>
                    <th class="text-center">
                      <h6>EXISTENCIA MINIMA</h6>
                    </th>
                    <th class="text-center">
                      <h6>EXISTENCIA</h6>
                    </th>
                    <th class="text-center">
                      <h6>INGRESAR</h6>
                    </th>
                    <th class="text-center">
                      <h6>RETIRAR</h6>
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

<div class="modal fade" id="modalRellenar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">RELLENAR EXISTENCIA</h5>
      </div>
      <form id="formRellenar" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('COSTO UNITARIO:', 'costo', 'number', '', true, 3, 'step="0.001"'); ?>
          <?= renderInput('CANTIDAD A INGRESAR:', 'cant', 'number', '', true, 3, 'step="0.0000001"'); ?>
          <?= renderInput('CONCEPTO DEL INGRESO:', 'observacion', 'textarea', '', true, 3); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control form-control-lg">INGRESAR</button>
          <button type="reset" class="btn btn-danger form-control form-control-lg"
            data-bs-dismiss="modal">CERRAR</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

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
  invProduccionMain.classList.add('active')
  invProduccionList.classList.add('active')

  let url = ['modulos/inventarioProduccion/inventarioProduccionLista.php', [11, 12, 13]]
  let IDTemporal

  const peticionPostActualizar = async (url, form, modal) => {
    const respuesta = await peticionAjaxPOST(url, form)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      form.reset()
      $(modal).modal('hide')
    }
  }

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
        ajaxEnviarInformacionGET('modulos/inventarioProduccion/inventarioProduccionEliminar.php?id=' + this.value)
      }
    })
  })

  $(document).on('click', '.btnRetirar', function() {
    IDTemporal = this.value
    $('#modalRetirar').modal('show')
    $('#modalRetirar').on('shown.bs.modal', function() {
      formRetirar.cant.focus()
    })
  })

  $(document).on('click', '.btnRellenar', function() {
    IDTemporal = this.value
    $('#modalRellenar').modal('show')
    $('#modalRellenar').on('shown.bs.modal', function() {
      formRellenar.costo.focus()
    })
  })

  formRellenar.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA CANTIDAD SERA INGRESADA AL INVENTARIO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionPostActualizar(
          'modulos/inventarioProduccion/inventarioProduccionRellenar.php?id=' + IDTemporal, formRellenar,
          '#modalRellenar')
      }
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
        peticionPostActualizar('modulos/inventarioProduccion/inventarioProduccionRetirar.php?id=' +
          IDTemporal, formRetirar, '#modalRetirar')
      }
    })
  })

  ajaxListaInformacionGET(url)
</script>