<aside class="<?= ($_GET['vista'] == 'Vender') ? 'sidebar-nav-wrapper active' : 'sidebar-nav-wrapper' ?>">
  <div class="navbar-logo">
    <a href="Bienvenidos">
      <h3 style="font-size: 18px;"><?= $_SESSION['PlantaGas']['Planta'] ?></h3>
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
            <a href="VenderFacturasEnEsperas" id="facturaEnEspera">Facturas En Espera</a>
          </li>
        </ul>
      </li>
    </ul>
    <span class="divider">
      <hr>
    </span>
</aside>
<div class="<?= ($_GET['vista'] == 'Vender') ? 'overlay active' : 'overlay' ?>"></div>
<main class="<?= ($_GET['vista'] == 'Vender') ? 'main-wrapper active' : 'main-wrapper' ?>">
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
                    <h6><?= $_SESSION['PlantaGas']['nombreUsuario']; ?></h6>
                  </div>
                </div>
                <i class="lni lni-chevron-down"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                <li>
                  <a href="#">
                    <i class="lni lni-cash-app"></i>TASA DIA:
                    <?= round(floatval($_SESSION['PlantaGas']['Dolar']), 4); ?>
                  </a>
                </li>
                <li>
                  <a href="http://localhost/maquinaFiscal/config.php" target="_blank">
                    <i class="lni lni-cogs"></i>MAQUINA FISCAL
                  </a>
                </li>
                <li>
                  <a href="MiPerfil">
                    <i class="lni lni-user"></i> Mi Perfil
                  </a>
                </li>
                <li>
                  <a href="#0" id="btnCerrarSesion"> <i class="lni lni-exit"></i> Cerrar
                    Sesion </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>