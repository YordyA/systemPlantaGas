<?php require_once 'modulos/main.php'; ?>
<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>CAMBIO DE MONEDA EXTRANGERA</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div>
              <h2 class="mb-3 text-center">CAMBIO DE DOLARES</h2>
              <form class="row" id="formularioUSD">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">FECHA</label>
                    <input type="date" class="form-control form-control-lg" name="fechaBillete" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">CAJA BILLETE</label>
                    <select class="form-select form-select-lg text-bold" name="NroCaja" required>
                      <option selected value="">SELECCIONE</option>
                      <option value="<?php echo Encriptar(1); ?>">CAJA 1</option>
                      <option value="<?php echo Encriptar(2); ?>">CAJA 2</option>
                      <option value="<?php echo Encriptar(3); ?>">CAJA 3</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label text-dark">BILLETE A DESCAMBIAR</label>
                    <select class="form-select form-select-lg text-bold" name="billeteADescambiar" required>
                      <option selected>SELECCIONE</option>
                      <option value="<?php echo (1); ?>">1$</option>
                      <option value="<?php echo (2); ?>">2$</option>
                      <option value="<?php echo (5); ?>">5$</option>
                      <option value="<?php echo (10); ?>">10$</option>
                      <option value="<?php echo (20); ?>">20$</option>
                      <option value="<?php echo (50); ?>">50$</option>
                      <option value="<?php echo (100); ?>">100$</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
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
                            <h6 id="denom1">0</h6>
                          </td>
                          <td>
                            <h6 id="denom2">0</h6>
                          </td>
                          <td>
                            <h6 id="denom5">0</h6>
                          </td>
                          <td>
                            <h6 id="denom10">0</h6>
                          </td>
                          <td>
                            <h6 id="denom20">0</h6>
                          </td>
                          <td>
                            <h6 id="denom50">0</h6>
                          </td>
                          <td>
                            <h6 id="denom100">0</h6>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="1">1$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="2">2$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="5">5$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD " style="font-size: 22px; font-weight: bold;" value="10">10$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="20">20$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="50">50$</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteUSD" style="font-size: 22px; font-weight: bold;" value="100">100$</button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="text-center">
                  <button class="main-btn primary-btn btn-hover m-1">
                    <strong>CAMBIAR BILLETE</strong>
                  </button>
                  <button type="reset" class="main-btn danger-btn btn-hover m-1">
                    <strong>CANCELAR</strong>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="card-style mb-30">
            <div>
              <h2 class="mb-3 text-center">CAMBIO DE PESOS COLOMBIANOS</h2>
              <form class="row" id="formularioCOP">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">FECHA</label>
                    <input type="date" class="form-control form-control-lg" name="fechaBillete" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">CAJA BILLETE</label>
                    <select class="form-select form-select-lg text-bold" name="NroCaja" required>
                      <option selected value="">SELECCIONE</option>
                      <option value="<?php echo Encriptar(1); ?>">CAJA 1</option>
                      <option value="<?php echo Encriptar(2); ?>">CAJA 2</option>
                      <option value="<?php echo Encriptar(3); ?>">CAJA 3</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label text-dark">BILLETE A DESCAMBIAR</label>
                    <select class="form-select form-select-lg text-bold" name="billeteADescambiar" required>
                      <option selected value="">SELECCIONE</option>
                      <option value="100">100COP</option>
                      <option value="200">200COP</option>
                      <option value="500">500COP</option>
                      <option value="1000">1.000COP</option>
                      <option value="2000">2.000COP</option>
                      <option value="5000">5.000COP</option>
                      <option value="10000">10.000COP</option>
                      <option value="20000">20.000COP</option>
                      <option value="50000">50.000COP</option>
                      <option value="100000">100.000COP</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="table-responsive table-sm">
                    <table class="table text-center">
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
                            <h6 id="denom50COP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom100COP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom200COP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom500COP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom1kCOP">0</h6>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="50">50COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="100">100COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="200">200COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="500">500COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="1000">1.000COP</button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="table-responsive table-sm">
                    <table class="table text-center">
                      <thead>
                        <tr>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>2.000COP</strong></h6>
                          </th>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>5.000COP</strong></h6>
                          </th>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>10.000COP</strong></h6>
                          </th>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>20.000COP</strong></h6>
                          </th>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>50.000COP</strong></h6>
                          </th>
                          <th>
                            <h6 class="text-center">DENOMINACION <br><strong>100.000COP</strong></h6>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <h6 id="denom2kCOP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom5kCOP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom10kCOP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom20kCOP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom50kCOP">0</h6>
                          </td>
                          <td>
                            <h6 id="denom100kCOP">0</h6>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="2000">2.000COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="5000">5.000COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="10000">10.000COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="20000">20.000COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="50000">50.000COP</button>
                            </div>
                          </td>
                          <td>
                            <div class="p-2">
                              <button type="button" class="btn btn-outline-success form-control btnBilleteCOP" style="font-size: 22px; font-weight: bold;" value="100000">100.000COP</button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="text-center">
                  <button class="main-btn primary-btn btn-hover m-1">
                    <strong>CAMBIAR BILLETE</strong>
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
    </div>
  </div>
</section>

<script>
  facturacionMain.classList.add('active')
  facturacionCambioDivisas.classList.add('active')

  const btnBilleteUSD = document.querySelectorAll('.btnBilleteUSD')
  const btnBilleteCOP = document.querySelectorAll('.btnBilleteCOP')

  let totalBilletesUSD = 0
  let billeteDescambioUSD = {
    1: 0,
    2: 0,
    5: 0,
    10: 0,
    20: 0,
    50: 0,
    100: 0
  }

  let totalBilletesCOP = 0
  let billeteDescambioCOP = {
    50: 0,
    100: 0,
    200: 0,
    500: 0,
    1000: 0,
    2000: 0,
    5000: 0,
    10000: 0,
    20000: 0,
    50000: 0,
    100000: 0
  }

  //! FUNCIONES USD

  const limpiarUSD = () => {
    totalBilletesUSD = 0
    billeteDescambioUSD = {
      1: 0,
      2: 0,
      5: 0,
      10: 0,
      20: 0,
      50: 0,
      100: 0
    }
    tablaTemporalUSD()
  }

  const tablaTemporalUSD = () => {
    denom1.innerHTML = billeteDescambioUSD[1]
    denom2.innerHTML = billeteDescambioUSD[2]
    denom5.innerHTML = billeteDescambioUSD[5]
    denom10.innerHTML = billeteDescambioUSD[10]
    denom20.innerHTML = billeteDescambioUSD[20]
    denom50.innerHTML = billeteDescambioUSD[50]
    denom100.innerHTML = billeteDescambioUSD[100]
  }

  const enviarDatosDelCambioUSD = async () => {
    const data = new FormData(formularioUSD)
    data.append('billete1', billeteDescambioUSD[1])
    data.append('billete2', billeteDescambioUSD[2])
    data.append('billete5', billeteDescambioUSD[5])
    data.append('billete10', billeteDescambioUSD[10])
    data.append('billete20', billeteDescambioUSD[20])
    data.append('billete50', billeteDescambioUSD[50])
    data.append('billete100', billeteDescambioUSD[100])
    const peticion = await fetch('modulos/controlDivisas/controlDivisasDescambiarUSD.php', {
      method: 'POST',
      body: data
    })
    const respuesta = await peticion.json()
    if (respuesta.alerta == 'limpiar') {
      limpiarUSD()
      Swal.fire({
        icon: respuesta.tipo,
        title: respuesta.titulo,
        text: respuesta.texto,
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        allowOutsideClick: false,
      }).then(result => {
        if (result.isConfirmed) {
          formularioUSD.reset()
        }
      })
    } else {
      Swal.fire({
        icon: respuesta.tipo,
        title: respuesta.titulo,
        text: respuesta.texto,
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        allowOutsideClick: false,
      })
    }
  }

  formularioUSD.billeteADescambiar.addEventListener('change', (e) => {
    e.preventDefault()
    limpiarUSD()
  })

  btnBilleteUSD.forEach(btn => {
    btn.addEventListener('click', (e) => {
      t = parseInt(e.target.value)
      a = totalBilletesUSD + t
      b = parseInt(formularioUSD.billeteADescambiar.value)

      if (b >= a) {
        billeteDescambioUSD[e.target.value] += 1
        totalBilletesUSD += parseInt(e.target.value)
        tablaTemporalUSD()
      }
    })
  })

  formularioUSD.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL BILLETE SERA DESCAMBIADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosDelCambioUSD()
      }
    })
  })

  formularioUSD.addEventListener('reset', (e) => {
    limpiarUSD()
  })


  //! TABLA TEMPORAL COP

  const limpiarCOP = () => {
    totalBilletesCOP = 0
    billeteDescambioCOP = {
      50: 0,
      100: 0,
      200: 0,
      500: 0,
      1000: 0,
      2000: 0,
      5000: 0,
      10000: 0,
      20000: 0,
      50000: 0,
      100000: 0
    }
    tablaTemporalCOP()
  }

  const tablaTemporalCOP = () => {
    denom50COP.innerHTML = billeteDescambioCOP[50]
    denom100COP.innerHTML = billeteDescambioCOP[100]
    denom200COP.innerHTML = billeteDescambioCOP[200]
    denom500COP.innerHTML = billeteDescambioCOP[500]
    denom1kCOP.innerHTML = billeteDescambioCOP[1000]
    denom2kCOP.innerHTML = billeteDescambioCOP[2000]
    denom5kCOP.innerHTML = billeteDescambioCOP[5000]
    denom5kCOP.innerHTML = billeteDescambioCOP[5000]
    denom10kCOP.innerHTML = billeteDescambioCOP[10000]
    denom20kCOP.innerHTML = billeteDescambioCOP[20000]
    denom50kCOP.innerHTML = billeteDescambioCOP[50000]
    denom100kCOP.innerHTML = billeteDescambioCOP[100000]
  }

  const enviarDatosDelCambioCOP = async () => {
    const data = new FormData(formularioCOP)
    data.append('billete50', billeteDescambioCOP[50])
    data.append('billete100', billeteDescambioCOP[100])
    data.append('billete200', billeteDescambioCOP[200])
    data.append('billete500', billeteDescambioCOP[500])
    data.append('billete1k', billeteDescambioCOP[1000])
    data.append('billete2k', billeteDescambioCOP[2000])
    data.append('billete5k', billeteDescambioCOP[50000])
    data.append('billete10k', billeteDescambioCOP[10000])
    data.append('billete20k', billeteDescambioCOP[20000])
    data.append('billete50k', billeteDescambioCOP[50000])
    data.append('billete100k', billeteDescambioCOP[100000])
    const peticion = await fetch('modulos/controlDivisas/controlDivisasDescambiarCOP.php', {
      method: 'POST',
      body: data
    })
    const respuesta = await peticion.json()
    if (respuesta.alerta == 'limpiar') {
      limpiarCOP()
      Swal.fire({
        icon: respuesta.tipo,
        title: respuesta.titulo,
        text: respuesta.texto,
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        allowOutsideClick: false,
      }).then(result => {
        if (result.isConfirmed) {
          formularioCOP.reset()
        }
      })
    } else {
      Swal.fire({
        icon: respuesta.tipo,
        title: respuesta.titulo,
        text: respuesta.texto,
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        allowOutsideClick: false,
      })
    }
  }

  formularioCOP.billeteADescambiar.addEventListener('change', (e) => {
    e.preventDefault()
    limpiarCOP()
  })


  btnBilleteCOP.forEach(btn => {
    btn.addEventListener('click', (e) => {
      t = parseInt(e.target.value)
      a = totalBilletesCOP + t
      b = parseInt(formularioCOP.billeteADescambiar.value)

      if (b >= a) {
        billeteDescambioCOP[e.target.value] += 1
        totalBilletesCOP += parseInt(e.target.value)
        tablaTemporalCOP()
      }
    })
  })

  formularioCOP.addEventListener('submit', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL BILLETE SERA DESCAMBIADO',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        enviarDatosDelCambioCOP()
      }
    })
  })

  formularioCOP.addEventListener('reset', (e) => {
    limpiarCOP()
  })
</script>