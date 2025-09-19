<div class="overlay"></div>
<main class="main-wrapper" style="margin-left: 0; padding-bottom: 0;">
  <section class="signin-section">
    <div class="container-fluid p-3">
      <div class="row g-0 auth-row shadow-lg">
        <div class="col-lg-6">
          <div class="auth-cover-wrapper">
            <div class="auth-cover">
              <div class="cover-image">
                <img src="logo.png" height="450px">
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="signin-wrapper">
            <div class="form-wrapper">
              <h3 class="mb-15">INICIAR SESIÓN</h3>
              <form action="" method="post">
                <div class="row">
                  <div class="col-12">
                    <div class="input-style-1">
                      <label>USUARIO:</label>
                      <input type="text" placeholder="Usuario" name="usuario" required>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="input-style-1">
                      <label>CONTRASEÑA:</label>
                      <input type="password" placeholder="Contraseña" name="clave" required>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="button-group d-flex justify-content-center flex-wrap">
                      <button type="submit" class="main-btn primary-btn btn-hover w-100 text-center">
                        INGRESAR
                      </button>
                    </div>
                  </div>
                  <div class="col-12 mt-2">
                    <?php
                    if (isset($_POST['usuario']) && isset($_POST['clave'])) {
                      require_once './modulos/main.php';
                      require_once './modulos/usuarios/usuariosMain.php';
                      require_once './modulos/iniciarSesion.php';
                    }
                    ?>
                  </div>
                </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>