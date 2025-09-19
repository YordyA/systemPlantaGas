<script src="./assets/js/main.js"></script>
<script>
  btnSalir.addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
      title: '¿ESTA SEGURO?',
      text: 'EL USUARIO SALDRA DEL SISTEMA',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'CERRAR SESION',
      cancelButtonText: 'CANCELAR'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = './inc/logout.php'
      }
    })
  })
</script>