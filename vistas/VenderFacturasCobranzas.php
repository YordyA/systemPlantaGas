<section class="table-components">
	<div class="container-fluid">
		<div class="title-wrapper pt-30">
			<div class="row align-items-center">
				<div class="col-md-12">
					<div class="title mb-30">
						<h2>CxC PENDIENTES</h2>
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
									<label class="form-label text-dark text-bold">DEL</label>
									<input type="date" id="del" class="form-control form-control-lg">
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label text-dark text-bold">HASTA</label>
									<input type="date" id="hasta" class="form-control form-control-lg">
								</div>
							</div>
							<div class="col-md-12">
								<div class="d-flex mb-3 text-center">
									<a id="exportar" class="btn btn-warning form-control form-control-lg" href="#">EXPORTAR REPORTE
										PDF</a>
								</div>
							</div>
						</div>
						<hr>
						<div class="table-wrapper table-responsive">
							<table class="table text-center" id="Tabla">
								<thead>
									<tr>
										<th class="text-center">
											<h6>FECHA</h6>
										</th>
										<th class="text-center">
											<h6>N FACTURA <br> (SISTEMA)</h6>
										</th>
										<th class="text-center">
											<h6>N FACTURA <br>(FISCAL)</h6>
										</th>
										<th class="text-center">
											<h6>RIF / CEDULA</h6>
										</th>
										<th class="text-center">
											<h6>RAZON SOCIAL</h6>
										</th>
										<th class="text-center">
											<h6>TOTAL BS</h6>
										</th>
										<th class="text-center">
											<h6>VER DETALLE FACTURA</h6>
										</th>
										<th class="text-center">
											<h6>COBRAR</h6>
										</th>
									</tr>
								</thead>
								<tbody id="TablaInformacion"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="modalFactura" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="modalTitleId">FACTURA</h3>
			</div>
			<div class="modal-body">
				<h4 class="modal-title" id="modalTitleId">DATOS DEL CLIENTE</h4>
				<table class="table text-center" id="Tabla">
					<thead>
						<tr>
							<th class="text-center">
								<h4>NRO VENTA</h4>
							</th>
							<th class="text-center">
								<h4>NRO FACTURA</h4>
							</th>
							<th class="text-center">
								<h4>RIF/CEDULA</h4>
							</th>
							<th class="text-center">
								<h4>RAZON SOCIAL</h4>
							</th>
							<th class="text-center">
								<h4>ESTATUS</h4>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="NroVenta"></td>
							<td id="NroFactura"></td>
							<td id="Rif"></td>
							<td id="Nombre"></td>
							<td id="Estatus"></td>
						</tr>
					</tbody>
				</table>
				<br>
				<table class="table text-center" id="Tabla">
					<thead>
						<tr>
							<th class="text-center">
								<h5>CODIGO</h5>
							</th>
							<th class="text-center">
								<h5>ARTICULO</h5>
							</th>
							<th class="text-center">
								<h5>CANTIDAD</h5>
							</th>
							<th class="text-center">
								<h5>P/U</h5>
							</th>
							<th class="text-center">
								<h5>TOTAL</h5>
							</th>
						</tr>
					</thead>
					<tbody id="tabla"></tbody>
				</table>
				<div class="modal-footer flex-nowrap">
					<button type="reset" class="btn btn-danger form-control" data-bs-dismiss="modal">CERRAR</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPagar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="modalTitleId">MONTOS ABONADOS</h3>
			</div>
			<div class="modal-body">
				<table class="table text-center" id="Tabla">
					<thead>
						<tr>
							<th class="text-center">
								TOTAL FACUTRA
							</th>
							<th class="text-center">
								TOTAL ABONADO
							</th>
							<th class="text-center">
								TOTAL RESTANTE
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="TotalFactura"></td>
							<td id="Abonado"></td>
							<td id="Restante"></td>
						</tr>
					</tbody>
				</table>
				<table class="table text-center" id="Tabla">
					<thead>
						<tr>
							<th class="text-center">
								EFECTIVO
							</th>
							<th class="text-center">
								TARJETA
							</th>
							<th class="text-center">
								BIOPAGO
							</th>
							<th class="text-center">
								PAGO M/TRANSF
							</th>
							<th class="text-center">
								CRUCES POR FACT
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="Efectivo"></td>
							<td id="Tarjeta"></td>
							<td id="Biopago"></td>
							<td id="PagoM"></td>
							<td id="Cruces"></td>
						</tr>
					</tbody>
				</table>
				<br>
				<form id="formulario" autocomplete="off">
					<div class="modal-footer flex-nowrap">
						<div class="row">
							<div class="col">
								<label class="form-label text-dark">MONTO EFECTIVO</label>
								<input type="number" name="efectivo" step="0.01" class="form-control" required value="0.00">
							</div>
							<div class="col">
								<label class="form-label text-dark">MONTO TARJETA</label>
								<input type="number" name="tarjeta" step="0.01" class="form-control" required value="0.00">
							</div>
							<div class="col">
								<label class="form-label text-dark">MONTO BIOPAGO</label>
								<input type="number" name="bioPago" step="0.01" class="form-control" required value="0.00">
							</div>
							<div class="col">
								<label class="form-label text-dark">MONTO PAGO M/TRANF</label>
								<input type="number" name="pagoMovil" step="0.01" class="form-control" required value="0.00">
							</div>
							<div class="col">
								<label class="form-label text-dark">MONTO POR CURCES FACT</label>
								<input type="number" name="cruces" step="0.01" class="form-control" required value="0.00">
							</div>
						</div>
					</div>
					<div class="text-center">
						<div class="modal-footer flex-nowrap">
							<button type="submit" class="btn btn-primary form-control">GUARDAR ABONOS</button>
						</div>
					</div>
					<div class="modal-footer flex-nowrap">
						<button type="reset" class="btn btn-danger form-control" data-bs-dismiss="modal">CERRAR</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	facturacionMain.classList.add('active')
	VenderFacturasCobranzas.classList.add('active')
	$("#del").val(fecha_hoy())
	$("#hasta").val(fecha_hoy())

	let url = ['modulos/reportes/ReporteFacturasEmitidasCxC.php?del=' + del.value + '&hasta=' + hasta.value, []]

	let IDTemporal

	function fecha_hoy() {
		var d = new Date($.now());
		var year = d.getFullYear();
		var mes_temporal = d.getMonth() + 1;
		var mes = mes_temporal < 10 ? "0" + mes_temporal : mes_temporal;
		var dia = d.getDate() < 10 ? "0" + d.getDate() : d.getDate();
		return year + "-" + mes + "-" + dia;
	}

	del.addEventListener('change', (e) => {
		e.preventDefault()
		data_table.destroy()
		ListaInformacion(['modulos/reportes/ReporteFacturasEmitidasCxC.php?del=' + del.value + '&hasta=' + hasta
			.value
		], [])
	})

	hasta.addEventListener('change', (e) => {
		e.preventDefault()
		data_table.destroy()
		ListaInformacion(['modulos/reportes/ReporteFacturasEmitidasCxC.php?del=' + del.value + '&hasta=' + hasta
			.value
		], [])
	})

	const factura = async (id) => {
		const peticion = await fetch('modulos/reportes/VisualizarFacturasEmitida.php?id=' + id)
		const respuesta = await peticion.json()
		NroVenta.innerHTML = respuesta.NroVenta
		NroFactura.innerHTML = respuesta.NroFactura
		Rif.innerHTML = respuesta.Rif
		Nombre.innerHTML = respuesta.Nombre
		Estatus.innerHTML = respuesta.Estatus
		tabla.innerHTML = respuesta.tabla
		$("#modalFactura").modal("show")
	}

	const pagar = async (id) => {
		const peticion = await fetch('modulos/reportes/CobrarCxC.php?id=' + id)
		const respuesta = await peticion.json()
		Efectivo.innerHTML = respuesta.Efectivo
		Tarjeta.innerHTML = respuesta.Tarjeta
		Biopago.innerHTML = respuesta.Biopago
		PagoM.innerHTML = respuesta.PagoM
		Cruces.innerHTML = respuesta.Cruces
		Abonado.innerHTML = respuesta.Abonado
		TotalFactura.innerHTML = respuesta.TotalFactura
		Restante.innerHTML = respuesta.Restante
		tabla.innerHTML = respuesta.tabla
		$('#modalPagar').modal('show')
	}

	$(document).on('click', '.factura', function() {
		factura(this.value)
	});

	$(document).on('click', '.pagar', function() {
		IDTemporal = this.value
		pagar(this.value)
	});


	exportar.addEventListener('click', (e) => {
		e.preventDefault()
		if (del.value != '' && hasta.value != '') {
			window.open('modulos/pdf/CxCReporte.php?del=' + del.value + '&hasta=' + hasta
				.value)
		}
	})

	formulario.addEventListener('submit', (e) => {
		e.preventDefault()
		Swal.fire({
			title: '¿ESTA SEGURO?',
			text: 'EL ABONO SERA REGISTRADO',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'ACEPTAR',
			cancelButtonText: 'CANCELAR'
		}).then((result) => {
			if (result.isConfirmed) {
				EnviarInformacion('modulos/cobrar/CobrarCxC.php?n=' + IDTemporal)
			}
		})
	})

	ListaInformacion(url)
</script>