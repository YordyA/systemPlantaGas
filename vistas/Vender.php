<?php if ($_SESSION['PlantaGas']['PesajeInicial'] == 0) {
  echo '<section class="table-components">
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
                    <h1 class="text-center text-bold text-danger">DEBE INICIAR EL INVENTARIO PARA FACTURAR</h1>
                    <h2 class="text-bold text-primary text-center">
                      <div class="d-flex justify-content-center">
                        <img src="logo.svg" width="450px">
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>';
  echo "<script>
        facturacionMain.classList.add('active')
        venderPuntoVenta.classList.add('active')
      </script>";
  exit();
}
require_once './modulos/main.php';
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>PUNTO DE VENTA</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card-style settings-card-2 mb-30">
        <div class="row">
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" id="btnPagar" class="btn btn-success form-control form-control-lg">
                <kbd>F6</kbd>
                COBRAR
              </button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" id="btnCancelar" class="btn btn-danger form-control form-control-lg">
                <kbd>SUPR</kbd>
                CANCELAR VENTA
              </button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" id="btnFacturaEspera" class="btn btn-warning form-control form-control-lg">
                FACTURA EN ESPERA
              </button>
            </div>
          </div>
          <hr>
          <div class="col-md-7">
            <div class="mb-3">
              <form id="form1" autocomplete="off">
                <input type="number" name="codigo" class="form-control form-control-lg text-bold"
                  placeholder="AGREGAR PRODUCTO" required>
              </form>
            </div>
            <div class="mb-3">
              <div class="table-wrapper table-responsive">
                <table class="table text-center" id="Tabla">
                  <thead>
                    <tr class="table-dark">
                      <th class="text-center">
                        DESCRIPCION
                      </th>
                      <th class="text-center">
                        P/U
                      </th>
                      <th class="text-center">
                        CANTIDAD
                      </th>
                      <th class="text-center">
                        SUBTOTAL
                      </th>
                      <th class="text-center">
                        QUITAR
                      </th>
                    </tr>
                  </thead>
                  <tbody id="TablaInformacion"></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div>
              <div class="alert alert-success">
                <div class="mb-3">
                  <form id="form2" autocomplete="off">
                    <input type="text" name="rif" class="form-control form-control-lg text-bold"
                      placeholder="RIF/CEDULA" required>
                  </form>
                </div>
                <div>
                  <hr>
                  <div class="mb-3">
                    <h4 class="text-bold text-dark d-flex justify-content-between">RIF/CEDULA: <span id="rif"></span>
                    </h4>
                  </div>
                  <hr>
                  <div class="mb-3">
                    <h4 class="text-bold text-dark d-flex justify-content-between">RAZON SOCIAL: <span
                        id="cliente"></span>
                    </h4>
                  </div>
                  <div class="alert alert-info">
                    <h4 class="text-bold mb-1 text-dark">TASA DE CAMBIO: Bs.
                      <?= number_format($_SESSION['PlantaGas']['Dolar'], 2) ?></h4>
                    <h1 class="text-dark text-bold"><strong>TOTALES:</strong></h1>
                    <div class="text-end">
                      <h1><strong>Bs.</strong> <span id="totalBS"></span></h1>
                      <h1><strong>$.</strong> <span id="totalUSD"></span></h1>
                      <h1><strong>Cop.</strong> <span id="totalCOP"></span></h1>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalRegistrarCliente" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          REGISTRAR CLIENTE
        </h5>
      </div>
      <form id="formulario" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('RIF / CEDULA', 'rif', 'text', '', true, 3, 'readonly'); ?>
          <?= renderInput('NOMBRE', 'nombre', 'text', '', true, 3); ?>
          <?= renderInput('APELLIDO', 'apellido', 'text', '', true, 3); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control form-control-lg">
            REGISTRAR
          </button>
          <button type="reset" class="btn btn-danger form-control form-control-lg" data-bs-dismiss="modal">
            CANCELAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAgregarCantidad" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          CANTIDAD
        </h5>
      </div>
      <form id="form3" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('CODIGO PRODUCTO', 'codigo', 'text', '', true, 3); ?>
          <?= renderInput('CANTIDAD A VENDER', 'cantidad', 'number', '', true, 3, 'step="0.001"'); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control form-control-lg">
            AGREGAR PRODUCTO
          </button>
          <button type="reset" class="btn btn-danger form-control form-control-lg" data-bs-dismiss="modal">
            CANCELAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditarCantidad" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          ACTUALIZAR CANTIDAD
        </h5>
      </div>
      <form id="form4" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('CANTIDAD A VENDER', 'cantidad', 'number', '', true, 3, 'step="0.001"'); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control">
            ACTUALIZAR CANTIDAD
          </button>
          <button type="reset" class="btn btn-danger form-control form-control-lg" data-bs-dismiss="modal">
            CANCELAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditarPrecio" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          APLICAR DESCUENTO
        </h5>
      </div>
      <form id="formEditPrecio" autocomplete="off">
        <div class="modal-body">
          <?= renderInput('PORCENTAJE DE DESCUENTO', 'porcentajeDescuento', 'number', '', true, 3, 'step="0.01"'); ?>
          <?= renderInput('USUARIO GERENTE', 'usuario', 'text', '', true, 3); ?>
          <?= renderInput('CONTRASEÑA', 'clave', 'text', '', true, 3); ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary form-control form-control-lg">
            ACTUALIZAR PRECIO
          </button>
          <button type="reset" class="btn btn-danger form-control form-control-lg" data-bs-dismiss="modal">
            CANCELAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- //! MODAL DE COBRO -->
<div class="modal fade" id="modalCobrar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          PUNTO DE VENTA (COBRAR)
        </h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div>
            <div class="table-responsive">
              <table class="table table-sm text-center">
                <thead>
                  <tr>
                    <th>
                      EFECTIVO
                      <br>
                      <kbd>F1</kbd>
                    </th>
                    <th>
                      EFECTIVO USD
                      <br>
                      <kbd>F5</kbd>
                    </th>
                    <th>
                      EFECTIVO COP
                      <br>
                      <kbd>F8</kbd>
                    </th>
                    <th>
                      BIOPAGO
                      <br>
                      <kbd>F2</kbd>
                    </th>
                    <th>
                      TARJETA
                      <br>
                      <kbd>F3</kbd>
                    </th>
                    <th>
                      PAGO MOVIL
                      <br>
                      <kbd>F4</kbd>
                    </th>
                    <th>
                      TRANSFERENCIA
                      <br>
                      <kbd>F7</kbd>
                    </th>
                    <th>
                      COBRAR
                      <br>
                      <kbd>F6</kbd>
                    </th>
                    <th>
                      LIMPIAR MEDIOS PAGO
                      <br>
                      <kbd>F11</kbd>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
            <div>
              <div class="p-1">
                <h3 class="d-flex justify-content-between text-bold">EFECTIVO:
                  <strong>Bs. <span id="efectivo"></span></strong>
                </h3>
              </div>
              <div class="p-1">
                <h3 class="d-flex justify-content-between text-bold">PUNTO DE VENTA:
                  <strong>Bs. <span id="tarjeta"></span></strong>
                </h3>
              </div>
              <div class="p-1">
                <h3 class="d-flex justify-content-between text-bold">BIOPAGO:
                  <strong>Bs. <span id="biopago">0.00</span></strong>
                </h3>
              </div>
              <div class="p-1">
                <h3 class="d-flex justify-content-between text-bold">TRANSFERENCIA:
                  <strong>Bs. <span id="transferencia">0.00</span></strong>
                </h3>
              </div>
              <div class="p-1">
                <h3 class="d-flex justify-content-between text-bold">OTRAS MONEDAS:
                  <div class="d-flex flex-column align-items-end">
                    <strong>Cop. <span id="cop">0.00</span></strong>
                    <strong>$. <span id="usd">0.00</span></strong>
                  </div>
                </h3>
              </div>
            </div>
            <hr>
            <div>
              <h2 class="d-flex justify-content-between text-bold">
                ABONADO
                <div id="totalAbonado"></div>
              </h2>
            </div>
            <div id="faltaYVuelto"></div>
            <div>
              <h2 class="d-flex justify-content-between text-bold">
                TOTAL
                <div class="alert alert-success">
                  <strong class="text-dark">Bs. <span id="totalModalBS"></span></strong>
                  <br>
                  <strong class="text-dark">$. <span id="totalModalUSD"></span></strong>
                  <br>
                  <strong class="text-dark">Cop. <span id="totalModalCOP"></span></strong>
                </div>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCobrarEfectivo" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          COBRAR EN EFECTIVO (BOLIVARES)
        </h5>
      </div>
      <div class="modal-body">
        <form id="formEfectivo">
          <div class="mb-3">
            <label class="form-label text-dark">CANTIDAD EFECTIVO</label>
            <input type="number" name="cantidad" class="form-control form-control-lg text-bold" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalBiopago" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          BIOPAGO
        </h5>
      </div>
      <div class="modal-body">
        <form id="formBiopago">
          <div class="mb-3">
            <h6 class="form-label text-dark">MONTO BIOPAGO</h6>
            <input type="number" name="cantidad" class="form-control form-control-lg text-bold" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTarjeta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          PUNTO DE VENTA (TARJETA)
        </h5>
      </div>
      <div class="modal-body">
        <form id="formTarjeta">
          <div class="mb-3">
            <h6 class="form-label text-dark">MONTO TARJETA</h6>
            <input type="number" name="cantidad" class="form-control form-control-lg text-bold" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPagoMovil" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          PAGO MOVIL
        </h5>
      </div>
      <div class="modal-body">
        <form id="formPagoMovil" autocomplete="off">
          <div class="mb-3">
            <h6 class="form-label text-dark">BANCO</h6>
            <select class="form-select form-select-lg text-bold" name="banco">
              <option value="">SELECCIONE</option>
              <option value="0102">0102 - BANCO DE VENEZUELA, S.A. BANCO UNIVERSAL</option>
              <option value="0007">0175 - BANCO BICENTENARIO</option>
              <option value="0104">0104 - BANCO VENEZOLANO DE CREDITO S.A.</option>
              <option value="0156">0156 - 100%BANCO</option>
              <option value="0172">0172 - BANCAMIGA BANCO MICROFINANCIERO, C.A.</option>
              <option value="0114">0114 - BANCO DEL CARIBE C.A.</option>
              <option value="0171">0171 - BANCO ACTIVO BANCO COMERCIAL, C.A.</option>
              <option value="0166">0166 - BANCO AGRICOLA</option>
              <option value="0128">0128 - BANCO CARONI, C.A. BANCO UNIVERSAL</option>
              <option value="0163">0163 - BANCO DEL TESORO</option>
              <option value="0115">0115 - BANCO EXTERIOR C.A.</option>
              <option value="0151">0151 - FONDO COMUN</option>
              <option value="0173">0173 - BANCO INTERNACIONAL DE DESARROLLO, C.A.</option>
              <option value="0105">0105 - BANCO MERCANTIL C.A.</option>
              <option value="0191">0191 - BANCO NACIONAL DE CREDITO</option>
              <option value="0138">0138 - BANCO PLAZA</option>
              <option value="0137">0137 - SOFITASA</option>
              <option value="0168">0168 - BANCRECER S.A. BANCO DE DESARROLLO</option>
              <option value="0134">0134 - BANESCO BANCO UNIVERSAL</option>
              <option value="0177">0177 - BANFANB</option>
              <option value="0146">0146 - BANGENTE</option>
              <option value="0174">0174 - BANPLUS BANCO COMERCIAL C.A</option>
              <option value="0108">0108 - BANCO PROVINCIAL BBVA</option>
              <option value="0157">0157 - DELSUR BANCO UNIVERSAL</option>
              <option value="0169">0169 - MIBANCO BANCO DE DESARROLLO, C.A.</option>
              <option value="0178">0178 - BANCO N58 BANCO DIGITAL BANCO MICROFINANCIERO S.A</option>
            </select>
          </div>
          <div class="mb-3">
            <h6 class="form-label text-dark">CEDULA DEL PAGADOR</h6>
            <input type="text" name="cedula" class="form-control form-control-lg text-bold" required>
          </div>
          <div class="mb-3">
            <h6 class="form-label text-dark">TELEFONO DEL PAGADOR</h6>
            <input type="number" name="telefono" class="form-control form-control-lg text-bold" required>
          </div>
          <div class="mb-3">
            <h6 class="form-label text-dark">MONTO DE LA TRANSFERENCIA</h6>
            <input type="number" name="cantidad" class="form-control form-control-lg text-bold" step="0.01" required>
          </div>
          <div class="mb-3">
            <h6 class="form-label text-dark">NRO DE REFERENCIA</h6>
            <input type="text" name="referencia" class="form-control form-control-lg text-bold" required>
          </div>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTransferencia" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          TRANSFERENCIA
        </h5>
      </div>
      <div class="modal-body">
        <form id="formTransferencia" autocomplete="off">
          <div class="mb-3">
            <h6 class="form-label text-dark">MONTO DE LA TRANSFERENCIA</h6>
            <input type="number" name="cantidad" class="form-control form-control-lg text-bold" step="0.01" required>
          </div>
          <div class="mb-3">
            <h6 class="form-label text-dark">NRO DE REFERENCIA</h6>
            <input type="text" name="referencia" class="form-control form-control-lg text-bold" required>
          </div>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCOP" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          MODENAS EXTRANJERA (COP)
        </h5>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50); ?>">50</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100); ?>">100</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(200); ?>">200</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop "
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(500); ?>">500</strong>
              </button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(1000); ?>">1.000</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(2000); ?>">2.000</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(5000); ?>">5.000</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(10000); ?>">10.000</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(20000); ?>">20.000</button>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50000); ?>">50.000</button>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBilliteCop"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100000); ?>">100.000</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalUSD" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          MODENAS EXTRANJERA (COP)
        </h5>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(1); ?>">1</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(2); ?>">2</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(5); ?>">5</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite "
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(10); ?>">10</strong>
              </button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(20); ?>">20</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50); ?>">50</button>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <button type="button" class="btn btn-outline-success form-control btnBillite"
                style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100); ?>">100</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalVuelto" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
  aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          VUELTO
        </h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-sm text-center">
              <thead>
                <tr>
                  <th>
                    VUELTO EN BOLIVARES
                    <br>
                    <kbd>F9</kbd>
                  </th>
                  <th>
                    VUELTO PAGO MOVIL
                    <br>
                    <kbd>F10</kbd>
                  </th>
                  <th>
                    LIMPIAR VUELTO
                    <br>
                    <kbd>SUPR</kbd>
                  </th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h2>VUELTO A ENTREGAR:</h2>
            <div>
              <h3 class="text-end"><strong>Bs. </strong><span id="totalVueltoAEntregarBs"></span></h3>
              <h3 class="text-end"><strong>$. </strong><span id="totalVueltoAEntregarUsd"></h3>
              <h3 class="text-end"><strong>Cop. </strong><span id="totalVueltoAEntregarCop"></h3>
            </div>
          </div>
          <div class="col-md-6">
            <h2>VUELTO ENTREGADO:</h2>
            <div>
              <h3 class="text-end"><strong>Bs. </strong><span id="totalVueltoEntregadoBs"></span>
              </h3>
              <h3 class="text-end"><strong>$. </strong><span id="totalVueltoEntregadoUsd"></h3>
              <h3 class="text-end"><strong>Cop. </strong><span id="totalVueltoEntregadoCop"></h3>
            </div>
          </div>
        </div>
        <hr>
        <div class="row mb-3">
          <div>
            <h3 class="text-center text-bold">DOLARES</h3>
            <div class="table-responsive table-sm">
              <table class="table text-center">
                <thead>
                  <tr>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>1$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>2$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>5$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>10$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>20$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>50$</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>100$</strong></h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <h6 id="denom1"></h6>
                    </td>
                    <td>
                      <h6 id="denom2"></h6>
                    </td>
                    <td>
                      <h6 id="denom5"></h6>
                    </td>
                    <td>
                      <h6 id="denom10"></h6>
                    </td>
                    <td>
                      <h6 id="denom20"></h6>
                    </td>
                    <td>
                      <h6 id="denom50"></h6>
                    </td>
                    <td>
                      <h6 id="denom100"></h6>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(1); ?>">1</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(2); ?>">2</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(5); ?>">5</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto "
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(10); ?>">10</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(20); ?>">20</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50); ?>">50</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control btnBilleteVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100); ?>">100</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div>
            <h3 class="text-center text-bold">PESOS COLOMBIANO</h3>
            <div class="table-responsive">
              <table class="table text-center table-sm">
                <thead>
                  <tr>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>50COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>100COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>200COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>500COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <br><strong>1.000COP</strong></h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <h6 id="denomCop50"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop100"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop200"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop500"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop1000"></h6>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50); ?>">50</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100); ?>">100</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(200); ?>">200</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(500); ?>">500</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(1000); ?>">1.000</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="table-responsive">
              <table class="table text-center ">
                <thead>
                  <tr>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>2.000COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>5.000COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>10.000COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>20.000COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>50.000COP</strong></h6>
                    </th>
                    <th>
                      <h6 class="text-center">DENOMINACION <strong>100.000COP</strong></h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <h6 id="denomCop2000"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop5000"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop10000"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop20000"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop50000"></h6>
                    </td>
                    <td>
                      <h6 id="denomCop100000"></h6>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(2000); ?>">2.000</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(5000); ?>">5.000</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(10000); ?>">10.000</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(20000); ?>">20.000</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(50000); ?>">50.000</button>
                      </div>
                    </td>
                    <td>
                      <div class="p-2">
                        <button type="button" class="btn btn-outline-success form-control bntBilleteCopVuelto"
                          style="font-size: 22px; font-weight: bold;" value="<?= Encriptar(100000); ?>">100.000</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalVueltoCuenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          MODAL VUELTO <i>(PAGO MOVIL)</i>
        </h5>
      </div>
      <form id="formVuelto">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-dark">TELEFONO DESTINO</label>
            <input type="number" class="form-control form-control-lg" name="tlfDestino" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">BANCO DESTINO</label>
            <select class="form-select form-select-lg text-bold" name="banco">
              <option value="" selected>SELECCIONE</option>
              <option value="0102">0102 - BANCO DE VENEZUELA, S.A. BANCO UNIVERSAL</option>
              <option value="0175">0175 - BANCO BICENTENARIO</option>
              <option value="0104">0104 - BANCO VENEZOLANO DE CREDITO S.A.</option>
              <option value="0156">0156 - 100%BANCO</option>
              <option value="0172">0172 - BANCAMIGA BANCO MICROFINANCIERO, C.A.</option>
              <option value="0114">0114 - BANCO DEL CARIBE C.A.</option>
              <option value="0171">0171 - BANCO ACTIVO BANCO COMERCIAL, C.A.</option>
              <option value="0166">0166 - BANCO AGRICOLA</option>
              <option value="0128">0128 - BANCO CARONI, C.A. BANCO UNIVERSAL</option>
              <option value="0163">0163 - BANCO DEL TESORO</option>
              <option value="0115">0115 - BANCO EXTERIOR C.A.</option>
              <option value="0151">0151 - FONDO COMUN</option>
              <option value="0173">0173 - BANCO INTERNACIONAL DE DESARROLLO, C.A.</option>
              <option value="0105">0105 - BANCO MERCANTIL C.A.</option>
              <option value="0191">0191 - BANCO NACIONAL DE CREDITO</option>
              <option value="0138">0138 - BANCO PLAZA</option>
              <option value="0137">0137 - SOFITASA</option>
              <option value="0168">0168 - BANCRECER S.A. BANCO DE DESARROLLO</option>
              <option value="0134">0134 - BANESCO BANCO UNIVERSAL</option>
              <option value="0177">0177 - BANFANB</option>
              <option value="0146">0146 - BANGENTE</option>
              <option value="0174">0174 - BANPLUS BANCO COMERCIAL C.A</option>
              <option value="0108">0108 - BANCO PROVINCIAL BBVA</option>
              <option value="0157">0157 - DELSUR BANCO UNIVERSAL</option>
              <option value="0169">0169 - MIBANCO BANCO DE DESARROLLO, C.A.</option>
              <option value="0178">0178 - BANCO N58 BANCO DIGITAL BANCO MICROFINANCIERO S.A</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success form-control form-control-lg">ENTEGRAR VUELTO</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalVueltoBS" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
  role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          MONTO VUELTO ENTREGADO EN BS
        </h5>
      </div>
      <div class="modal-body">
        <form id="formVueltoBS" autocomplete="off">
          <?= renderInput('MONTO VUELTO ENTREGADO EN BS:', 'cantidad', 'text', '', true, 3, 'step="0.001"'); ?>
          <button type="submit" class="btn btn-success form-control">ABONAR</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
facturacionMain.classList.add('active')
venderPuntoVenta.classList.add('active')

form2.rif.focus()

let IDtemporal
let modal = 0
let total = 0
let abonoTotalFacturaBs = 0
let montoMaximoPagoMovil = 0

const btnBilleteUSD = document.querySelectorAll('.btnBillite')
const btnBilleteUSDVuel = document.querySelectorAll('.btnBilleteVuelto')

const bntBilleteCOP = document.querySelectorAll('.btnBilliteCop')
const bntBilleteCOPVuel = document.querySelectorAll('.bntBilleteCopVuelto')

const listaVenta = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionListaCarrito.php')
  const respuesta = await peticion.json()

  rif.innerHTML = respuesta.rif
  cliente.innerHTML = respuesta.cliente
  TablaInformacion.innerHTML = respuesta.tabla

  totalModalBS.innerHTML = respuesta.totalBs
  totalModalUSD.innerHTML = respuesta.totalUsd
  totalModalCOP.innerHTML = respuesta.totalCop

  totalBS.innerHTML = respuesta.totalBs
  totalUSD.innerHTML = respuesta.totalUsd
  totalCOP.innerHTML = respuesta.totalCop

  //! METODOS DE PAGO
  efectivo.innerHTML = respuesta['medioPagos'].efectivo
  biopago.innerHTML = respuesta['medioPagos'].biopago
  tarjeta.innerHTML = respuesta['medioPagos'].tarjeta
  transferencia.innerHTML = respuesta['medioPagos'].transferencia
  usd.innerHTML = respuesta['medioPagos'].divisasUsd
  cop.innerHTML = respuesta['medioPagos'].divisasCop

  faltaYVuelto.innerHTML = respuesta.vueltoFalta
  totalAbonado.innerHTML = respuesta.abonado

  //! TOTAL FACTURA
  total = respuesta.totalFactura
  abonoTotalFacturaBs = respuesta.totalAbonadoBs

  //! VUELTO
  totalVueltoAEntregarBs.innerHTML = respuesta.vueltoAEntregarBs
  totalVueltoAEntregarUsd.innerHTML = respuesta.vueltoAEntregarUsd
  totalVueltoAEntregarCop.innerHTML = respuesta.vueltoAEntregarCop

  //! BILLETE VUELTO (DOLARES)
  denom1.innerHTML = respuesta['billeteVuelto'][1]
  denom2.innerHTML = respuesta['billeteVuelto'][2]
  denom5.innerHTML = respuesta['billeteVuelto'][5]
  denom10.innerHTML = respuesta['billeteVuelto'][10]
  denom20.innerHTML = respuesta['billeteVuelto'][20]
  denom50.innerHTML = respuesta['billeteVuelto'][50]
  denom100.innerHTML = respuesta['billeteVuelto'][100]

  //! BILLETE VUELTO (PESOS COLOMBIANOS)
  denomCop50.innerHTML = respuesta['billeteVueltoCop'][50]
  denomCop100.innerHTML = respuesta['billeteVueltoCop'][100]
  denomCop200.innerHTML = respuesta['billeteVueltoCop'][200]
  denomCop500.innerHTML = respuesta['billeteVueltoCop'][500]
  denomCop1000.innerHTML = respuesta['billeteVueltoCop'][1000]
  denomCop2000.innerHTML = respuesta['billeteVueltoCop'][2000]
  denomCop5000.innerHTML = respuesta['billeteVueltoCop'][5000]
  denomCop10000.innerHTML = respuesta['billeteVueltoCop'][10000]
  denomCop20000.innerHTML = respuesta['billeteVueltoCop'][20000]
  denomCop50000.innerHTML = respuesta['billeteVueltoCop'][50000]
  denomCop100000.innerHTML = respuesta['billeteVueltoCop'][100000]

  //! VUELTO ENTREGADO
  totalVueltoEntregadoBs.innerHTML = respuesta.vueltoEntregadoBs
  totalVueltoEntregadoUsd.innerHTML = respuesta.vueltoEntregadoUsd
  totalVueltoEntregadoCop.innerHTML = respuesta.vueltoEntregadoCop

  montoMaximoPagoMovil = parseFloat(respuesta.SinFormatVueltoEntregadoBs)
}

const rifCliente = async (id) => {
  const peticion = await fetch('modulos/facturacion/FacturacionAgregarCliente.php?id=' + id)
  const respuesta = await peticion.json()
  if (respuesta == 3) {
    listaVenta()
    form2.reset()
    form1.codigo.focus()
    Swal.fire({
      position: "center",
      icon: "success",
      title: "CLIENTE AGREGADO",
      showConfirmButton: false,
      timer: 1000
    })
  } else if (respuesta == 2) {
    formulario.rif.value = form2.rif.value
    form2.reset()
    $('#modalRegistrarCliente').modal('show')
    $("#modalRegistrarCliente").on("shown.bs.modal", function() {
      formulario.nombre.focus()
    })
  } else if (respuesta === 1) {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "EL FORMATO NO COINCIDE",
      showConfirmButton: false,
      timer: 1000
    })
  }
}

const registrarCliente = async () => {
  const peticion = await fetch('modulos/cliente/ClienteRegistrar.php', {
    method: 'POST',
    body: new FormData(formulario)
  })
  const respuesta = await peticion.json()
  if (respuesta.alerta == 'limpiar') {
    rifCliente(formulario.rif.value)
    formulario.reset()
    $('#modalRegistrarCliente').modal('hide')
  } else if (respuesta.alerta == 'rif') {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "RIF DEBE CONTENER 10 CARACTERES",
      showConfirmButton: false,
      timer: 1500
    })
  }
}

const agregarProducto = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionAgregarProducto.php', {
    method: 'POST',
    body: new FormData(form3)
  })
  const respuesta = await peticion.json()
  form3.reset()
  if (respuesta === 1) {
    listaVenta()
    $('#modalAgregarCantidad').modal('hide')
    Swal.fire({
      position: "center",
      icon: "success",
      title: "PRODUCTO AGREGADO",
      showConfirmButton: false,
      timer: 800
    })
    form1.codigo.focus()
  } else if (respuesta === 2) {
    $('#modalAgregarCantidad').modal('hide')
    form1.codigo.focus()
    Swal.fire({
      position: "center",
      icon: "error",
      title: "EXISTENCIA INSUFICIENTE",
      showConfirmButton: false,
      timer: 800
    })
  } else if (respuesta === 3) {
    $('#modalAgregarCantidad').modal('hide')
    form1.codigo.focus()
    Swal.fire({
      position: "center",
      icon: "error",
      title: "CODIGO INCORRECTO",
      showConfirmButton: false,
      timer: 800
    })
  } else if (respuesta === 4) {
    $('#modalAgregarCantidad').modal('hide')
    Swal.fire({
      position: "center",
      icon: "error",
      title: "INGRESE EL CLIENTE",
      showConfirmButton: false,
      timer: 800
    })
    form2.rif.focus()
  }
}

const actualizarCantidad = async (i, cantidad) => {
  const peticion = await fetch('modulos/facturacion/FacturacionActualizarCantidadProducto.php?i=' + i +
    '&cantidad=' + cantidad)
  const respuesta = await peticion.json()
  form4.reset()
  if (respuesta) {
    $('#modalEditarCantidad').modal('hide')
    listaVenta()
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "EXISTENCIA INSUFICIENTE",
      showConfirmButton: false,
      timer: 800
    })
  }
}

const quitarProducto = async (i) => {
  const peticion = await fetch('modulos/facturacion/FacturacionQuitarProducto.php?i=' + i)
  const respuesta = await peticion.json()
  if (respuesta) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: "PRODUCTO ELIMINADO",
      showConfirmButton: false,
      timer: 1000
    })
    listaVenta()
  }
}

const cancelarVenta = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionCancelar.php')
  const respuesta = await peticion.json()
  if (respuesta) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: "VENTA CANCELADA",
      showConfirmButton: false,
      timer: 800
    })
    form2.rif.focus()
    listaVenta()
  }
}

const cobrar = async (mp, formulario) => {
  const peticion = await fetch('modulos/facturacion/FacturacionCobrar.php?mp=' + modal, {
    method: 'post',
    body: new FormData(formulario)
  })
  const respuesta = await peticion.json()
  if (respuesta[0]) {
    formulario.reset()
    if (modal == 2) {
      $('#modalCobrarEfectivo').modal('hide')
    } else if (modal == 3) {
      $('#modalBiopago').modal('hide')
    } else if (modal == 4) {
      $('#modalTarjeta').modal('hide')
    } else if (modal == 5) {
      $('#modalPagoMovil').modal('hide')
    } else if (modal == 7) {
      $('#modalTransferencia').modal('hide')
    }
    modal = 1
    Swal.fire({
      position: "center",
      icon: "success",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1000
    })
    $('#modalCobrar').modal('show')
    listaVenta()
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1000
    })
  }
}

const crearVenta = async () => {
  if (abonoTotalFacturaBs > total && modal != 8) {
    $('#modalCobrar').modal('hide')
    modal = 8
    $('#modalVuelto').modal('show')
  } else if (modal == 8) {
    window.location.href = 'http://localhost/maquinaFiscal/index.php';
  } else if (abonoTotalFacturaBs == total) {
   window.location.href = 'http://localhost/maquinaFiscal/index.php';
  // window.location.href = 'modulos/cobrar/CobrarRegistrarVenta.php';
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: '¡DEBE CANCELAR EL TOTAL DE LA FACTURA!',
      showConfirmButton: false,
      timer: 1600
    })
  }
}

const FacturaEspera = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionFacturaEnEspera.php')
  const respuesta = await peticion.json()
  if (respuesta[0]) {
    listaVenta()
    Swal.fire({
      position: "center",
      icon: "success",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1100
    })
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1200
    })
  }
}

const actualizarPrecioEspecial = async (id) => {
  const peticion = await fetch('modulos/facturacion/FacturacionActualizarPrecioProducto.php?i=' + id, {
    method: 'POST',
    body: new FormData(formEditPrecio)
  })
  const respuesta = await peticion.json()
  if (respuesta[0]) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1900
    })
    listaVenta()
    formEditPrecio.reset()
    $('#modalEditarPrecio').modal('hide')
  } else {
    Swal.fire({
      position: "center",
      icon: 'false',
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1900
    })
  }
}

const ingresarBillete = async (md, id) => {
  const peticion = await fetch('modulos/facturacion/FacturacionCobrar.php?mp=' + md + '&id=' + id)
  const respuesta = await peticion.json()
  if (respuesta[0]) {
    if (md == 6) {
      $('#modalUSD').modal('hide')
    } else if (md == 9) {
      $('#modalCOP').modal('hide')
    }
    modal = 1
    Swal.fire({
      position: "center",
      icon: "success",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1000
    })
    $('#modalCobrar').modal('show')
    listaVenta()
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1000
    })
  }
}

const ingresarVueltoBillete = async (tipoModena, id) => {
  const peticion = await fetch('modulos/facturacion/FacturacionCobrarVuelto.php?id=' + id + '&tpb=' + tipoModena)
  const respuesta = await peticion.json()
  if (respuesta[0]) {
    if (modal == 12) {
      formVueltoBS.reset()
      modal = 8
      $('#modalVueltoBS').modal('hide')
      $('#modalVuelto').modal('show')
    }
    listaVenta()
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: respuesta[1],
      showConfirmButton: false,
      timer: 1800
    })
  }
}

const borrarVuelto = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionBorrarVuelto.php')
  const respuesta = await peticion.json()
  listaVenta()
}

const enviarInformacionPagoMovil = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionVueltoBDV.php', {
    body: new FormData(formVuelto),
    method: 'POST'
  })
  const respuesta = await peticion.json()
  console.log(respuesta)
  if (respuesta[0]) {
    formVuelto.reset()
    Swal.fire({
      title: respuesta[1].message,
      text: respuesta[1].referencia,
      icon: "success",
      showCancelButton: false,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ACEPTAR",
      allowOutsideClick: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'http://localhost/maquinaFiscal/index.php'
      }
    })
  } else {
    Swal.fire({
      icon: "error",
      title: "¡OCURRIO UN ERROR INESPERADO!",
      text: respuesta[1],
    })
  }
}

const limpiarMedioPago = async () => {
  const peticion = await fetch('modulos/facturacion/FacturacionMedioPagoCobrarLimpiar.php')
  const respuesta = await peticion.json()
  Swal.fire({
    position: "center",
    icon: (respuesta[0]) ? "success" : "error",
    title: respuesta[1],
    showConfirmButton: false,
    timer: 1200
  })
  if (respuesta[0]) {
    listaVenta()
  }
}

form2.addEventListener('submit', (e) => {
  e.preventDefault()
  rifCliente(form2.rif.value)
})

formulario.addEventListener('submit', (e) => {
  e.preventDefault()
  registrarCliente()
})

form1.addEventListener('submit', (e) => {
  e.preventDefault()
  form3.codigo.value = form1.codigo.value
  form1.reset()
  $('#modalAgregarCantidad').modal('show')
  $('#modalAgregarCantidad').on('shown.bs.modal', function() {
    form3.cantidad.focus()
  })
})

form3.addEventListener('submit', (e) => {
  e.preventDefault()
  agregarProducto()
})

$(document).on('click', '.editar', function() {
  IDtemporal = this.value
  $('#modalEditarCantidad').modal('show')
  $('#modalEditarCantidad').on('shown.bs.modal', function() {
    form4.cantidad.focus()
  })
})

form4.addEventListener('submit', (e) => {
  e.preventDefault()
  actualizarCantidad(IDtemporal, form4.cantidad.value)
})

$(document).on('click', '.quitar', function() {
  quitarProducto(this.value)
})

btnCancelar.addEventListener('click', (e) => {
  e.preventDefault()
  Swal.fire({
    title: '¿ESTA SEGURO?',
    text: "LA FACTURA SERA CANCELADO",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "ACEPTAR",
    cancelButtonText: "CANCELAR"
  }).then((result) => {
    if (result.isConfirmed) {
      cancelarVenta()
    }
  })
})

btnPagar.addEventListener('click', (e) => {
  e.preventDefault()
  $('#modalCobrar').modal('show')
  modal = 1
})

document.addEventListener('keydown', (e) => {

  switch (e.keyCode) {
    case 46:
      e.preventDefault()
      if (modal == 8) {
        borrarVuelto()
      } else {
        cancelarVenta()
      }
      break
    case 117:
      e.preventDefault()
      if (modal == 0) {
        $('#modalCobrar').modal('show')
        modal = 1
      } else if (modal == 1) {
        crearVenta()
      } else if (modal == 8) {
        if (montoMaximoPagoMovil != 0) {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "¡DEBE ENTREGAR EL VUELTO!",
            showConfirmButton: false,
            timer: 1600
          })
          return
        }
        modal = 8
        $('#modalCobrar').modal('hide')
        crearVenta()
      }
      break

    case 27:
      e.preventDefault()
      if (modal == 1) {
        modal = 0
        $('#modalCobrar').modal('hide')
      } else if (modal == 8) {
        modal = 1
        $('#modalVuelto').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 112:
      e.preventDefault()
      if (modal == 1) {
        modal = 2
        $("#modalCobrar").modal('hide')
        $('#modalCobrarEfectivo').modal('show')
        $("#modalCobrarEfectivo").on("shown.bs.modal", function() {
          formEfectivo.cantidad.focus()
        })
      } else if (modal == 2) {
        modal = 1
        formEfectivo.reset()
        $('#modalCobrarEfectivo').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 113:
      e.preventDefault()
      if (modal == 1) {
        modal = 3
        $('#modalCobrar').modal('hide')
        $('#modalBiopago').modal('show')
        $("#modalBiopago").on("shown.bs.modal", function() {
          formBiopago.cantidad.focus()
        })
      } else if (modal == 3) {
        modal = 1
        formBiopago.reset()
        $('#modalBiopago').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 114:
      e.preventDefault()
      if (modal == 1) {
        modal = 4
        $('#modalCobrar').modal('hide')
        $('#modalTarjeta').modal('show')
        $("#modalTarjeta").on("shown.bs.modal", function() {
          formTarjeta.cantidad.focus()
        })
      } else if (modal == 4) {
        modal = 1
        formTarjeta.reset()
        $('#modalTarjeta').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 115:
      e.preventDefault()
      if (modal == 1) {
        modal = 5
        $('#modalCobrar').modal('hide')
        $('#modalPagoMovil').modal('show')
        $("#modalPagoMovil").on("shown.bs.modal", function() {
          formPagoMovil.cantidad.focus()
        })
      } else if (modal == 5) {
        modal = 1
        formPagoMovil.reset()
        $('#modalPagoMovil').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 116:
      e.preventDefault()
      if (modal == 1) {
        modal = 6
        $('#modalCobrar').modal('hide')
        $('#modalUSD').modal('show')
      } else if (modal == 6) {
        modal = 1
        $('#modalUSD').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 118:
      e.preventDefault()
      if (modal == 1) {
        modal = 7
        $('#modalCobrar').modal('hide')
        $('#modalTransferencia').modal('show')
        $("#modalTransferencia").on("shown.bs.modal", function() {
          formTransferencia.cantidad.focus()
        })
      } else if (modal == 7) {
        modal = 1
        formTransferencia.reset()
        $('#modalTransferencia').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break

    case 119:
      e.preventDefault()
      if (modal == 1) {
        modal = 9
        $('#modalCobrar').modal('hide')
        $('#modalCOP').modal('show')
      } else if (modal == 9) {
        modal = 1
        $('#modalCOP').modal('hide')
        $('#modalCobrar').modal('show')
      }
      break
    case 120:
      e.preventDefault()
      if (modal == 12) {
        modal = 8
        $('#modalVueltoBS').modal('hide')
        $('#modalVuelto').modal('show')
      } else if (modal == 8) {
        modal = 12
        $('#modalVuelto').modal('hide')
        $('#modalVueltoBS').modal('show')
        $("#modalVueltoBS").on('shown.bs.modal', function() {
          formVueltoBS.cantidad.focus()
        })
      }
      break
    case 121:
      e.preventDefault()
      if (modal == 10) {
        modal = 8
        $('#modalVueltoCuenta').modal('hide')
        $('#modalVuelto').modal('show')
      } else if (modal == 8) {
        if (montoMaximoPagoMovil > parseFloat(600) || montoMaximoPagoMovil == 0) {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "¡MONTO NO PERMITIDO!",
            showConfirmButton: false,
            timer: 1600
          })
          return
        }
        modal = 10
        $('#modalVuelto').modal('hide')
        $('#modalVueltoCuenta').modal('show')
      }
      break
    case 122:
      e.preventDefault()
      if (modal == 1) {
        limpiarMedioPago()
      }
      break

    default:
      break
  }

})

formEfectivo.addEventListener('submit', (e) => {
  e.preventDefault()
  cobrar(modal, formEfectivo)
})

formBiopago.addEventListener('submit', (e) => {
  e.preventDefault()
  if (formBiopago.cantidad.value <= total) {
    cobrar(modal, formBiopago)
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: 'EL MONTO AGREGADO TIENE QUE SER MENOR O IGUAL',
      showConfirmButton: false,
      timer: 800
    })
  }
})

formTarjeta.addEventListener('submit', (e) => {
  e.preventDefault()
  if (formTarjeta.cantidad.value <= total) {
    cobrar(modal, formTarjeta)
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: 'EL MONTO AGREGADO TIENE QUE SER MENOR O IGUAL',
      showConfirmButton: false,
      timer: 800
    })
  }
})

formPagoMovil.addEventListener('submit', (e) => {
  e.preventDefault()
  cobrar(modal, formPagoMovil)
})

formTransferencia.addEventListener('submit', (e) => {
  e.preventDefault()
  if (formTarjeta.cantidad.value <= total) {
    cobrar(modal, formTransferencia)
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: 'EL MONTO AGREGADO TIENE QUE SER MENOR O IGUAL',
      showConfirmButton: false,
      timer: 800
    })
  }
})

btnFacturaEspera.addEventListener('click', (e) => {
  e.preventDefault()
  FacturaEspera()
})

$(document).on('click', '.editarPrecio', function() {
  IDtemporal = this.value
  $('#modalEditarPrecio').modal('show')
  $('#modalEditarPrecio').on('shown.bs.modal', function() {
    formEditPrecio.precioEspecial.focus()
  })
})

formEditPrecio.addEventListener('submit', (e) => {
  e.preventDefault()
  Swal.fire({
    title: '¿ESTA SEGURO?',
    text: "EL PRECIO ESPECIAL SERA INGRESADO A LA FACTURA",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "ACEPTAR",
    cancelButtonText: "CANCELAR"
  }).then((result) => {
    if (result.isConfirmed) {
      actualizarPrecioEspecial(IDtemporal)
    }
  })
})

btnBilleteUSD.forEach(usd => {
  usd.addEventListener('click', (e) => {
    ingresarBillete(modal, e.target.value)
  })
})

bntBilleteCOP.forEach(cop => {
  cop.addEventListener('click', (e) => {
    ingresarBillete(modal, e.target.value)
  })
})

btnBilleteUSDVuel.forEach(button => {
  button.addEventListener('click', (e) => {
    ingresarVueltoBillete(1, e.target.value)
  })
})

bntBilleteCOPVuel.forEach(cop => {
  cop.addEventListener('click', (e) => {
    ingresarVueltoBillete(2, e.target.value)
  })
})

formVuelto.addEventListener('submit', (e) => {
  e.preventDefault()
  Swal.fire({
    title: '¿ESTA SEGURO?',
    text: 'USTED REALIZARA UN PAGO MOVIL',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ACEPTAR',
    cancelButtonText: 'CANCELAR'
  }).then((result) => {
    if (result.isConfirmed) {
      enviarInformacionPagoMovil()
    }
  })
})

formVueltoBS.addEventListener('submit', (e) => {
  e.preventDefault()
  ingresarVueltoBillete(3, formVueltoBS.cantidad.value)
})


listaVenta()
</script>