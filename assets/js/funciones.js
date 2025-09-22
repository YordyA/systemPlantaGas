const alertas = alerta => {
  if (alerta.alerta === 'simple') {
    swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
    })
  } else if (alerta.alerta == 'limpiar') {
    Swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
    }).then(result => {
      if (result.isConfirmed) {
        formulario.reset()
      }
    })
  } else if (alerta.alerta == 'redireccionar') {
    Swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = alerta.url
      }
    })
  } else if (alerta.alerta == 'recargar') {
    Swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
    }).then(result => {
      if (result.isConfirmed) {
        window.location.reload()
      }
    })
  } else if (alerta.alerta == 'actualizacion') {
    if (alerta.modal !== '') {
      $(alerta.modal).modal('hide')
    }
    Swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ACEPTAR',
    }).then(result => {
      if (result.isConfirmed) {
        dataTable.destroy()
        ajaxListaInformacionGET(url)
      }
    })
  } else if (alerta.alerta == 'volver') {
    Swal.fire({
      icon: alerta.tipo,
      title: alerta.titulo,
      text: alerta.texto,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'ACEPTAR',
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = document.referrer
      }
    })
  } else if (alerta.alerta == 'temp') {
    Swal.fire({
      position: 'center',
      icon: alerta.tipo,
      title: alerta.texto,
      showConfirmButton: false,
      timer: 1900,
    })
  }
}

//* PETICIÓN AJAX ENVIAR DATOS POST
const ajaxEnviarInformacionPOST = async url => {
  const datos = new FormData(formulario)
  const peticion = await fetch(url, {
    method: 'POST',
    body: datos,
  })
  const respuesta = await peticion.json()
  return alertas(respuesta)
}

//* PETICIÓN AJAX RECUPERAR DATOS
const ajaxListaInformacionGET = async info => {
  const peticion = await fetch(info[0])
  const respuesta = await peticion.json()
  tablaInfo.innerHTML = respuesta
  dataTable = new DataTable('#tablaMain', {
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print'],
    pageLength: 120,
    destroy: true,
    columnDefs: [
      {
        orderable: false,
        targets: info[1],
      },
    ],
    language: {
      decimal: '',
      emptyTable: 'No hay información',
      info: 'Mostrando _START_ a _END_ de _TOTAL_ Entradas',
      infoEmpty: 'Mostrando 0 de 0 de 0 Entradas',
      infoFiltered: '(Filtrado de _MAX_ total entradas)',
      infoPostFix: '',
      thousands: ',',
      lengthMenu: 'Mostrar _MENU_ Entradas',
      loadingRecords: 'Cargando...',
      processing: 'Procesando...',
      search: 'Buscar:',
      zeroRecords: 'Sin resultados encontrados',
      paginate: {
        first: 'Primero',
        last: 'Ultimo',
        next: 'Siguiente',
        previous: 'Anterior',
      },
    },
  })
}

//* PETICIÓN AJAX PETICIÓN GET
const ajaxEnviarInformacionGET = async url => {
  const peticion = await fetch(url)
  const respuesta = await peticion.json()
  return alertas(respuesta)
}

//* GENERAR FECHA DEL DIA
function fechaHoy() {
  var d = new Date($.now())
  var year = d.getFullYear()
  var mes_temporal = d.getMonth() + 1
  var mes = mes_temporal < 10 ? '0' + mes_temporal : mes_temporal
  var dia = d.getDate() < 10 ? '0' + d.getDate() : d.getDate()
  return year + '-' + mes + '-' + dia
}

//* FUNCIÓN VOLVER ATRÁS
function volver() {
  window.location.href = document.referrer
}

//* PETICION AJAX GET SENCILLA
const peticionAjaxGET = async url => {
  const peticion = await fetch(url)
  const respuesta = await peticion.json()
  return respuesta
}

//* PETICION AJAX POST SENCILLA
const peticionAjaxPOST = async (url, form) => {
  const peticion = await fetch(url, {
    method: 'POST',
    body: new FormData(form),
  })
  const respuesta = await peticion.json()
  return respuesta
}

//* GENERAR GRAFICOS
const crearGrafico = async (tipo, idCanvas, url, label) => {
  Chart.register(ChartDataLabels)
  const data = await peticionAjaxGET(url)
  console.log(data)
  const ctx = document.getElementById(idCanvas).getContext('2d')
  graficaRepot = new Chart(ctx, {
    type: tipo, 
    data: {
      labels: data.labels, 
      datasets: [{
        label: label, 
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
      },
      scales: tipo === 'bar' ? {
        y: {
          beginAtZero: true
        }
      } : {}
    }
  })
}


/// PETICION AJAX PARA OBTENER DATOS
const ListaInformacion = async (info) => {
  const peticion = await fetch(info[0]);
  const respuesta = await peticion.json();
  TablaInformacion.innerHTML = respuesta;
  data_table = $("#Tabla").DataTable({
    dom: "Bfrtip",
    pageLength: 80,
    destroy: true,
    columnDefs: [
      {
        orderable: false,
        targets: info[1],
      },
    ],
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 de 0 de 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
};