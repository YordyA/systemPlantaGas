<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>CUADRE DE CAJA RESUMIDO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">

            <div class="row mb-3">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Del:</label>
                  <input type="date" id="del" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Hasta:</label>
                  <input type="date" id="hasta" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-warning form-control form-control-lg" id="btnPDF">VER
                    REPORTE PDF</button>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-info form-control form-control-lg" id="btnExcel">VER
                    REPORTE EXCEL</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>CUADRE DE CAJA DETALLADO</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">

            <div class="row mb-3">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Del:</label>
                  <input type="date" id="de" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Hasta:</label>
                  <input type="date" id="hast" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-warning form-control form-control-lg" id="btnPD">VER
                    REPORTE PDF</button>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-info form-control form-control-lg" id="btnExce">VER
                    REPORTE EXCEL</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>CONTROL DE DIVISAS</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row">
              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Del:</label>
                  <input type="date" id="delBilletes" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-warning form-control form-control-lg" id="btnPDFControlDivisas">
                    VER REPORTE PDF
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-info form-control form-control-lg" id="btnExcelControlDivisas">
                    VER REPORTE EXCEL
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>LIBRO DE VENTA</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="tables-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-style mb-30">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">HASTA:</label>
                  <input type="date" id="delLibroVenta" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">HASTA:</label>
                  <input type="date" id="hastaLibroVenta" require class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-3">
                  <button class="btn btn-info form-control form-control-lg" id="btnExcelLibroVenta">
                    VER REPORTE EXCEL
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<script>
  facturacionMain.classList.add('active')
  reporteCuadreDeCaja.classList.add('active')

  $("#del").val(fechaHoy())
  $("#hasta").val(fechaHoy())
  $("#de").val(fechaHoy())
  $("#hast").val(fechaHoy())

  delBilletes.value = fechaHoy()
  delLibroVenta.value = fechaHoy()
  hastaLibroVenta.value = fechaHoy()

  btnExcel.addEventListener('click', (e) => {
    window.open('modulos/reportesExcel/ReporteExcelCuadreDeCaja.php?i=' + del.value + '&f=' + hasta.value)
  })

  btnPDF.addEventListener('click', (e) => {
    window.open('modulos/pdf/CuadreDeCaja.php?i=' + del.value + '&f=' + hasta.value)
  })

  btnExce.addEventListener('click', (e) => {
    window.open('modulos/reportesExcel/ReporteExcelCuadreDeCaja.php?i=' + del.value + '&f=' + hasta.value)
  })

  btnPD.addEventListener('click', (e) => {
    window.open('modulos/pdf/CuadreDeCajaDetallado.php?i=' + de.value + '&f=' + hast.value)
  })

  btnExcelControlDivisas.addEventListener('click', (e) => {
    window.open('modulos/reportesExcel/ReporteExcelControlDivisas.php?f=' + delBilletes.value)
  })

  btnExcelLibroVenta.addEventListener('click', (e) => {
    window.open('modulos/reportesExcel/ExcelReportLibroDeVenta.php?d=' + delLibroVenta.value + '&h=' + hastaLibroVenta.value)
  })
</script>