<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>DESPACHO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <button type="button" id="btnCompletar" class="btn btn-success form-control form-control-lg">
                COMPLETAR
              </button>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <button type="button" id="btnCancelar" class="btn btn-danger form-control form-control-lg">
                CANCELAR
              </button>
            </div>
          </div>
          <hr>
          <div class="col-md-12">
            <?= renderInput('BUSCAR PRODUCTO:', '', 'text', '', true, 3, 'id="buscador"'); ?>
          </div>
          <hr>
          <div class="col-md-12">
            <div class="table-wrapper table-responsive">
              <table class="table text-center">
                <thead>
                  <tr>
                    <th>
                      <h6>CODIGO</h6>
                    </th>
                    <th>
                      <h6>DESCRICION</h6>
                    </th>
                    <th>
                      <h6>DISP</h6>
                    </th>
                    <th>
                      <h6>CANT</h6>
                    </th>
                    <th>
                      <h6>P/U</h6>
                    </th>
                    <th>
                      <h6>SUBTOTAL</h6>
                    </th>
                    <th>
                      <h6>ELIMINAR</h6>
                    </th>
                  </tr>
                </thead>
                <tbody id="tablaTemp"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalCantDesp" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          INGRESAR CANTIDAD A DESPACHAR
        </h5>
      </div>
      <form id="formCant">
        <div class="modal-body">
          <?= renderInput('CANTIDAD A DESPACHAR:', 'cantDesp', 'number', '', true); ?>
        </div>
        <div class="modal-footer nowrap">
          <button type="submit" class="btn btn-primary form-control form-control-lg">
            INGRESAR
          </button>
          <button type="reset" class="btn btn-danger form-control form-control-lg" data-bs-dismiss="modal">
            CERRAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  despachosMain.classList.add('active')
  despachos.classList.add('active')

  let IDTemporal

  const tablaTempInfo = async () => {
    const respuesta = await peticionAjaxGET('modulos/despachos/despachosListaTemp.php')
    tablaTemp.innerHTML = respuesta.tablaTempInfo
  }

  const peticionesPOST = async (url, form, modal) => {
    const respuesta = await peticionAjaxPOST(url, form)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      form.reset()
      $(modal).modal('hide')
      tablaTempInfo()
    }
  }

  const peticionesGET = async (url) => {
    const respuesta = await peticionAjaxGET(url)
    alertas(respuesta)
    if (respuesta.tipo === 'success') {
      tablaTempInfo()
    }
  }

  $(document).ready(function() {
    $('#buscador').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'modulos/despachos/despachosBuscarProductos.php',
          data: {
            buscador: request.term
          },
          dataType: 'JSON',
          success: function(data) {
            response(data)
          }
        })
      },
      minLength: 1,
      select: function(event, ui) {
        IDTemporal = ui.item.value
        buscador.value = ''
        $('#modalCantDesp').modal('show')
        $('#modalCantDesp').on('shown.bs.modal', function() {
          formCant.cantDesp.focus()
        })
        return false
      }
    })
  })

  formCant.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'LA CANTIDAD SERA INGRESADA AL DESPACHO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionesPOST('modulos/despachos/despachosAgregarProducto.php?id=' + IDTemporal,
          formCant, '#modalCantDesp')
      }
    })
  })

  $(document).on('click', '.btnCant', function() {
    IDTemporal = this.value
    $('#modalCantDesp').modal('show')
    $('#modalCantDesp').on('shown.bs.modal', function() {
      formCant.cantDesp.focus()
    })
  })

  $(document).on('click', '.btnEliminar', function() {
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL LOTE SERA ELIMINADO DEL DESPACHO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionesGET('modulos/despachos/despachosEliminar.php?id=' + this.value)
      }
    })
  })

  btnCancelar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL DESPACHO SERA CANCELADO ',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        peticionesGET('modulos/despachos/despachosCancelar.php')
      }
    })
  })

  btnCompletar.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'DESEA COMPLETAR EL DESPACHO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'despachosCompletar'
      }
    })
  })

  tablaTempInfo()
</script>