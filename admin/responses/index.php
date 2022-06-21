<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<style>
	.prod-img {
		width: 5em;
		max-height: 8em;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">Respuestas</h3>
		<div class="card-tools">
			<a href="./?page=responses/manage_response" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Crear Nueva</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha de Creación</th>
						<th>Respuesta</th>
						<th>Palabras Clave</th>
						<th>Estado</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * from `response_list`  order by unix_timestamp(`date_created`) desc ");
					while ($row = $qry->fetch_assoc()) :
						$kw_qry = $conn->query("SELECT * FROM keyword_list where response_id = '{$row['id']}'");
						$kws = array_column($kw_qry->fetch_all(MYSQLI_ASSOC), 'keyword');
						if (count($kws)) {
							$kws = implode(", ", $kws);
						} else {
							$kws = "N/A";
						}
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
							<td>
								<p class="truncate-1 m-0"><?php echo strip_tags($row['response']) ?></p>
							</td>
							<td><?= $kws ?></td>
							<td class="text-center">
								<?php if ($row['status'] == 1) : ?>
									<span class="badge badge-success px-3 rounded-pill">Activo</span>
								<?php else : ?>
									<span class="badge badge-danger px-3 rounded-pill">Inactivo</span>
								<?php endif; ?>
							</td>
							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Acción
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item view_data" href="./?page=responses/view_response&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item edit_data" href="./?page=responses/manage_response&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("¿Deseas eliminar esta respuesta de forma permanente?", "delete_response", [$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [6]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})

	function delete_response($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_response",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>