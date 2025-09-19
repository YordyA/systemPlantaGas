<?php
require_once './modulos/main.php';
require_once './modulos/despachos/despachosMain.php';
?>
<section class="section">
  <div class="container-fluid">
    <div class="title-wrapper pt-30">
      <div class="row align-items-center">
        <div class="col-md-10">
          <div class="title mb-30">
            <h2>GRAFICOS</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card-style settings-card-2 mb-30">
          <div class="row">
            <div class="col-md-3">
              <?= renderInput('DEL:', '', 'date', '', true, 3, 'id="dReport"'); ?>
            </div>
            <div class="col-md-3">
              <?= renderInput('HASTA:', '', 'date', '', true, 3, 'id="hReport"'); ?>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label text-dark">TIPO DESPACHO</label>
                <select class="form-select form-select-lg text-bold" id="IDTipoDesp" required>
                  <?php foreach (despachosTiposLista() as $row) : ?>
                    <option value="<?= encriptar($row['IDTipoDespacho']); ?>"><?= $row['DescripcionTipoDesp']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label text-dark">TIPO DE MEDIDA:</label>
                <select class="form-select form-select-lg text-bold" id="IDMedida" required>
                  <option value="<?= encriptar('kg'); ?>">KG</option>
                  <option value="<?= encriptar('und'); ?>">UND</option>
                </select>
              </div>
            </div>
            <hr>
            <div class="col-md-12">
              <div class="chart-container">
                <canvas id="despachosXArticulo" height="100px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card-style settings-card-2 mb-30">
          <div class="row">
            <div class="col-md-4">
              <?= renderInput('DEL:', '', 'date', '', true, 3, 'id="dReportDos"'); ?>
            </div>
            <div class="col-md-4">
              <?= renderInput('HASTA:', '', 'date', '', true, 3, 'id="hReportDos"'); ?>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label text-dark">TIPO DESPACHO</label>
                <select class="form-select form-select-lg text-bold" id="IDTipoDespDos" required>
                  <?php foreach (despachosTiposLista() as $row) : ?>
                    <option value="<?= encriptar($row['IDTipoDespacho']); ?>"><?= $row['DescripcionTipoDesp']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <hr>
            <div class="col-md-12">
              <div class="chart-container">
                <canvas id="despachoXArticuloPorcentaje" width="10px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script>
  despachosMain.classList.add('active')
  despachosReportMain.classList.add('active')
  despachosReportGraficos.classList.add('active')

  dReport.value = fechaHoy()
  hReport.value = fechaHoy()
  dReportDos.value = fechaHoy()
  hReportDos.value = fechaHoy()

  Chart.register(ChartDataLabels)

  const graficosDespachoosDistribucionXArticulo = async () => {
    const data = await peticionAjaxGET('modulos/despachos/reportDespachosGraficoDistribucionXArticulos.php?d=' +
      dReport.value + '&h=' + hReport.value + '&IDTipoDesp=' + IDTipoDesp.value + '&IDMedida=' + IDMedida.value)
    const ctx = document.getElementById('despachosXArticulo').getContext('2d')
    graficaRepot = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: '',
          data: data.datasets.data,
          backgroundColor: data.datasets.backgroundColor,
          borderColor: data.datasets.borderColor,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          datalabels: {
            anchor: 'center',
            align: 'center',
            display: true,
            font: {
              weight: 'bold',
              size: 12
            },
            color: 'black',
            formatter: (value) => value
          }
        }
      }
    })
  }

  const graficosDespachoosDistribucionXArticuloPorcentaje = async () => {
    const data = await peticionAjaxGET(
      'modulos/despachos/reportDespachosGraficoDistribucionXArticulosPorcentaje.php?d=' +
      dReportDos.value + '&h=' + hReportDos.value + '&IDTipoDesp=' + IDTipoDespDos.value)
    console.log(data)
    const ctx = document.getElementById('despachoXArticuloPorcentaje').getContext('2d')
    graficaRepotPorcentaje = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: data.labels,
        datasets: [{
          label: '',
          data: data.datasets.data,
          backgroundColor: data.datasets.backgroundColor,
          borderColor: data.datasets.borderColor,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          datalabels: {
            anchor: 'center',
            align: 'center',
            display: true,
            font: {
              weight: 'bold',
              size: 12
            },
            color: 'black',
            formatter: (value) => value
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      }
    })
  }

  dReport.addEventListener('change', (e) => {
    graficaRepot.destroy()
    graficosDespachoosDistribucionXArticulo()
  })

  hReport.addEventListener('change', (e) => {
    graficaRepot.destroy()
    graficosDespachoosDistribucionXArticulo()
  })

  IDMedida.addEventListener('change', (e) => {
    graficaRepot.destroy()
    graficosDespachoosDistribucionXArticulo()
  })

  IDTipoDesp.addEventListener('change', (e) => {
    graficaRepot.destroy()
    graficosDespachoosDistribucionXArticulo()
  })

  dReportDos.addEventListener('change', (e) => {
    graficaRepotPorcentaje.destroy()
    graficosDespachoosDistribucionXArticuloPorcentaje()
  })

  hReportDos.addEventListener('change', (e) => {
    graficaRepotPorcentaje.destroy()
    graficosDespachoosDistribucionXArticuloPorcentaje()
  })

  IDTipoDespDos.addEventListener('change', (e) => {
    graficaRepotPorcentaje.destroy()
    graficosDespachoosDistribucionXArticuloPorcentaje()
  })

  graficosDespachoosDistribucionXArticuloPorcentaje()
  graficosDespachoosDistribucionXArticulo()
</script>