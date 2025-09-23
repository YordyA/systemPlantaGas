<section class="table-components">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="title mb-30">
            <h2>FACTURACION POR ARTICULO</h2>
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
                <div class="">
                  <label class="form-label text-bold text-dark">Del:</label>
                  <input type="date" id="del" class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-bold text-dark">Hasta:</label>
                  <input type="date" id="hasta" class="form-control form-control-lg">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <button class="btn btn-warning form-control form-control-lg" id="btnPDF">VER REPORTE PDF</button>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb">
                  <button class="btn btn-info form-control form-control-lg" id="btnExcel">VER REPORTE EXCEL</button>
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
  reporteFacturacionPorArticulo.classList.add('active')

  $("#del").val(fecha_hoy())
  $("#hasta").val(fecha_hoy())

  function fecha_hoy() {
    var d = new Date($.now());
    var year = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = mes_temporal < 10 ? "0" + mes_temporal : mes_temporal;
    var dia = d.getDate() < 10 ? "0" + d.getDate() : d.getDate();
    return year + "-" + mes + "-" + dia;
  }

  btnPDF.addEventListener('click', (e) => {
    window.open('modulos/pdf/FacturacionPorArticulo.php?i=' + del.value + '&f=' + hasta.value)
  })

  btnExcel.addEventListener('click', (e) => {
    window.open('modulos/reportesExcel/ReporteExcelFacturacionPorArticulo.php?i=' + del.value + '&f=' + hasta.value)
  })
</script>