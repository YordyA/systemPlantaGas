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
            <a href="VenderFacturasEmitidasPorDespachar" id="VenderFacturasEmitidasPorDespachar">Cierre de Inventario</a>
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