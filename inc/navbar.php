<aside class="sidebar-nav-wrapper">
  <div class="navbar-logo">
    <a href="bienvenidos">
      <h4 class="m-1"><?= RAZONSOCIAL; ?></h4>
      <h6><?= RIF; ?></h6>
    </a>
  </div>
  <nav class="sidebar-nav">
    <ul>
            <span class="divider">
        <hr>
      </span>
      <li class="nav-item nav-item-has-children" id="facturacionMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_4" aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="icon">
            <i class="lni lni-cart-full"></i>
          </span>
          <span class="text">FACTURACION</span>
        </a>
        <ul id="ddmenu_4" class="collapse dropdown-nav">
          <li>
            <a href="Vender" id="venderPuntoVenta">Punto de Venta</a>
          </li>
          <li>
            <a href="VenderFacturasEnEspera" id="facturaEnEspera">Facturas En Espera</a>
          </li>
          <li>
            <a href="VenderFacturasEmitidas" id="venderFacturasEmitidas">Facturas Emitidas</a>
          </li>
          <li>
            <a href="VenderFacturasDonaciones" id="venderFacturasDonaciones">Donaciones</a>
          </li>
          <li>
            <a href="VenderFacturasEntregaProductos" id="VenderFacturasEntregaProductos">Entrega Productos</a>
          </li>
          <li>
            <a href="reporteCuadreDeCaja" id="reporteCuadreDeCaja">Cuadre de Caja</a>
          </li>
          <li>
            <a href="reporteFacturacionPorArticulo" id="reporteFacturacionPorArticulo">Facturacion Por Articulos</a>
          </li>
          <li>
            <a href=" reporteVueltosPagoMovil" id="reporteVueltosPagoMovil">Vueltos Pago Movil</a>
          </li>
          <li>
            <a href="MaquinaFIscalReporteZ" id="MaquinaFIscalReporteZ">Reporte Z</a>
          </li>
          <li>
            <a href="VenderFacturasCobranzas" id="VenderFacturasCobranzas">CxC</a>
          </li>
          <li>
            <a href="venderFacturacionHistorialCxC" id="venderFacturacionHistorialCxC">Historial de CxC</a>
          </li>
          <li>
            <a href="venderCambioCaja" id="facturacionCambioDivisas">Cambio Divisas</a>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="despachosMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#13">
          <span class=" icon">
            <i class="lni lni-delivery"></i>
          </span>
          <span class="text">DESPACHO</span>
        </a>
        <ul id="13" class="collapse dropdown-nav">
          <li>
            <a href="despachos" id="despachos"><strong>DESPACHO</strong></a>
          </li>
          <li class="nav-item nav-item-has-children" id="despachosReportMain">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#12">
              <span class="text">REPORTES</span>
            </a>
            <ul id="12" class="collapse dropdown-nav">
              <li>
                <a href="despachosReportRealizados" id="despachosReportRealizados">Despachos Realizados</a>
              </li>
              <li>
                <a href="despachosReportDistribucionXArticulo" id="despachosReportDistProducto">
                  Distribucion por Articulos
                </a>
              </li>
              <li>
                <a href="despachosReportExcel" id="despachosReportExcel">
                  Reportes Excel
                </a>
              </li>
              <li>
                <a href="despachosReportGraficos" id="despachosReportGraficos">Graficos</a>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-item-has-children" id="clientesMain">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#11">
              <span class="text">CLIENTES</span>
            </a>
            <ul id="11" class="collapse dropdown-nav">
              <li>
                <a href="clientesRegistrar" id="clientesReg">Registrar Cliente</a>
              </li>
              <li>
                <a href="clientesLista" id="clientesList">Lista Clientes</a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="inventarioPlantaMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#10">
          <span class=" icon">
            <i class="lni lni-dropbox"></i>
          </span>
          <span class="text">INVENTARIO PLANTA</span>
        </a>
        <ul id="10" class="collapse dropdown-nav">
          <li>
            <a href="almacenRegistrar" id="almacenRegistrar">Registrar Almacen</a>
          </li>
          <li>
            <a href="inventarioPlantaLista" id="inventarioPlantaList">Inventario Planta</a>
          </li>
           <li>
            <a href="InventarioConteoFisicoInicial" id="InventarioConteoFisicoInicial">Apertura de Inventario</a>
          </li>
          <li>
            <a href="InventarioConteoFisicoFinal" id="InventarioConteoFisicoFinal">Cierre de Inventario</a>
          </li>
          <li>
            <a href="productosPresentacion" id="productosPresentacion">Tipos de Presentacion</a>
          </li>
          <li class="nav-item nav-item-has-children" id="inventarioPlantaReportMain">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#9">
              <span class="text">REPORTES</span>
            </a>
            <ul id="9" class="collapse dropdown-nav">
              <li>
                <a href="inventarioPlantaReportMovimiento" id="inventarioPlantaReportMov">Movimiento de Inventario
                  Planta</a>
              </li>
              <li>
                <a href="inventarioPlantaEntreFechas"
                  id="inventarioPlantaEntreFechas">
                  Movimiento de Inventario Planta Entre Fechas</a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="produccionMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#8">
          <span class=" icon">
            <i class="lni lni-shopping-basket"></i>
          </span>
          <span class="text">PRODUCCIÓN</span>
        </a>
        <ul id="8" class="collapse dropdown-nav">
          <!-- <li>
            <a href="articulosRegistrar" id="articulosReg">Registrar Articulos</a>
          </li> -->
          <li>
            <a href="formulasRegistrar" id="formulasReg">Registrar Formula</a>
          <li>
          <li>
            <a href="inventarioProduccionRegistrarCostos" id="inventarioProduccionRegistrarCostos">Registrar Costos</a>
          <li>
            <a href="produccionRegistrar" id="produccionReg">Registrar Producción</a>
          </li>
          <li>
            <a href="produccionEnProceso" id="produccionEnProceso">Producciones En Proceso</a>
          </li>

          <li class="nav-item nav-item-has-children" id="produccionReportMain">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#7">
              <span class="text">REPORTES</span>
            </a>
            <ul id="7" class="collapse dropdown-nav">
              <li>
                <a href="costosLista" id="costosLista">Lista de Conceptos Costos</a>
              </li>
              <li>
                <a href="articulosLista" id="articulosList">Lista de Articulos</a>
              </li>
              <li>
                <a href="formulasLista" id="formulasList">Lista de Formulas</a>
              </li>
              <li>
                <a href="produccionReportHistorial" id="produccionReportHistorial">Historial de Producción</a>
              </li>
              <li>
                <a href="produccionReportGraficos" id="produccionReportGraficos">Graficos</a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="invProduccionMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#6">
          <span class=" icon">
            <i class="lni lni-leaf"></i>
          </span>
          <span class="text">INVENTARIO</span>
        </a>
        <ul id="6" class="collapse dropdown-nav">
          <li>
            <a href="inventarioProductosRegistrar" id="invProduccionReg">
              Registrar Productos
            </a>
          </li>
          <li>
            <a href="inventarioProduccionLista" id="invProduccionList">
              Inventario Materia Prima e Insumos
            </a>
          </li>
          <li>
            <a href="inventarioProduccionConteo" id="invProduccionListConteo">
              Realizar Conteo Físico
            </a>
          </li>
          <li class="nav-item nav-item-has-children" id="invProduccionReportMain">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#5">
              <span class="text">REPORTES</span>
            </a>
            <ul id="5" class="collapse dropdown-nav">
              <li>
                <a href="inventarioProduccionReportMovimiento" id="invProduccionReportMov">
                  Movimiento Materia Prima e Insumos
                </a>
              </li>
              <li>
                <a href="inventarioProduccionMovimientoEntreFechas"
                  id="inventarioProduccionMovimientoEntreFechas">Movimiento Materia Prima e Insumos Entre Fechas</a>
              </li>
              <li>
                <a href="inventarioProduccionConteoReport" id="invProduccionReportConteos">
                  Lista de Conteos Físico
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="flotaMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#2">
          <span class=" icon">
            <i class="lni lni-delivery"></i>
          </span>
          <span class="text">FLOTA</span>
        </a>
        <ul id="2" class="collapse dropdown-nav">
          <li>
            <a href="cisternaRegistrar" id="cisternaRegistrar">Registrar Cisterna</a>
          </li>
          <li>
            <a href="cisternasLista" id="cisternasLista">Lista Cisternas</a>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
      <li class="nav-item nav-item-has-children" id="usuariosMain">
        <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#0">
          <span class=" icon">
            <i class="lni lni-user"></i>
          </span>
          <span class="text">USUARIOS</span>
        </a>
        <ul id="0" class="collapse dropdown-nav">
          <li>
            <a href="usuariosRegistrar" id="usuariosReg">Registrar Usuario</a>
          </li>
          <li>
            <a href="usuariosLista" id="usuariosList">Lista de Usuarios</a>
          </li>
        </ul>
      </li>
      <span class="divider">
        <hr />
      </span>
    </ul>
  </nav>
</aside>
<div class="overlay"></div>
<main class="main-wrapper">
  <header class="header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-6">
          <div class="header-left d-flex align-items-center">
            <div class="menu-toggle-btn mr-20">
              <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                <i class="lni lni-chevron-left me-2"></i> Menu
              </button>
            </div>
          </div>
        </div>
        <div class="col-lg-7 col-md-7 col-6">
          <div class="header-right">
            <div class="profile-box ml-15">
              <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="profile-info">
                  <div class="info">
                    <h6><?php echo $_SESSION['PlantaGas']['nombreUsuario']; ?></h6>
                  </div>
                </div>
                <i class="lni lni-chevron-down"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                <li>
                  <a href="miPerfil">
                    <i class="lni lni-user"></i> Mi Perfil
                  </a>
                </li>
                                <li>
                  <a href="#">
                    <i class="lni lni-cash-app"></i>TASA DIA:
                    <?= round(floatval($_SESSION['PlantaGAs']['Dolar']), 4); ?>
                  </a>
                </li>
                <li>
                  <a href="http://localhost/maquinaFiscal/config.php" target="_blank">
                    <i class="lni lni-cogs"></i>MAQUINA FISCAL
                  </a>
                </li>
                <li>
                  <a href="#0" id="btnSalir"> <i class="lni lni-exit"></i>
                    Cerrar Sesion
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>