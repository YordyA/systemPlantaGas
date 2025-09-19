<?php
require_once './modulos/main.php';
require_once './modulos/productos/productosMain.php';
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>REGISTRAR PRODUCTO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card-style settings-card-2 mb-30">
          <form id="formulario" autocomplete="off">
            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">TIPO DE PRODUCTO</label>
                  <select class="form-select form-select-lg text-bold" name="IDTipoProducto" required>
                    <option selected value="">SELECCIONE</option>
                    <?php foreach (TiposProductos() as $row): ?>
                      <option value="<?= encriptar($row['IDTipo']); ?>">
                        <?= $row['DescripcionTipo']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">ALICUOTA:</label>
                  <select class="form-select form-select-lg text-bold" name="IDAlicuota" required>
                    <option selected value="">SELECCIONE</option>
                    <option value="<?= encriptar(0); ?>">NINGUNA</option>
                    <option value="<?= encriptar(0.16); ?>">16%</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <?= renderInput('PRECIO DE VENTA (SIN IVA):', 'precioUnitario', 'number', '', true, 3, 'step="0.01"'); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('CODIGO DEL PRODUCTO:', 'codigoProducto', 'text', '', true); ?>
              </div>
              <div class="col-md-6">
                <?= renderInput('DECRIPCION DEL PRODUCTO:', 'descripcionProducto', 'text', '', true); ?>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="main-btn primary-btn btn-hover m-1">
                <strong>REGISTRAR</strong>
              </button>
              <button type="reset" class="main-btn danger-btn btn-hover m-1">
                <strong>CANCELAR</strong>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  invProduccionMain.classList.add('active')
  invProduccionReg.classList.add('active')

  formulario.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL PRODUCTO SERA REGISTRADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        ajaxEnviarInformacionPOST('modulos/inventarioProductos/ProductosRegistrar.php')
      }
    })
  })
</script>