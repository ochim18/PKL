		<div class="page-title text-center">
			<h2>Dashboard</h2>
		</div>
		<div class="page-content mb-3">
        	<?php get_message_flash() ?>
			<div class="row">
				<div class="col-sm-6">
					<div class="card mb-3 clearfix border-primary">
						<div class="card-body">
							<div class="media">
								<?php if ($logo = get_option('logo')): ?>
									<img class="d-flex align-self-start mr-3" src="<?php url('uploads/'.$logo) ?>" alt="Logo" style="max-height: 100px">
								<?php endif ?>
								<div class="media-body">
									<h4 class="mt-0"><?php echo get_option('lembaga_name') ?></h4>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card mb-3 clearfix border-success">
						<div class="card-header border-success text-white bg-success clearfix">
							<h1 class="float-right"><i class="fa fa-book"></i></h1>
							<h1><?php echo count($jumlah_buku); ?></h1>
							<span>Jumlah Buku Tercatat</span>
						</div>
						<div class="card-footer border-success">
							<a href="<?php url('databuku') ?>" class="text-success card-footer-link">
								<i class="float-right fa fa-arrow-right"></i>
								View Details
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card mb-3 clearfix border-warning">
						<div class="card-header border-warning text-white bg-warning clearfix">
							<h1 class="float-right"><i class="fa fa-file-text"></i></h1>
							<h1><?php echo count($jumlah_jenis); ?></h1>
							<span>Jumlah Jenis Semua Kategori</span>
						</div>
						<div class="card-footer border-warning">
							<a href="<?php url('kategori') ?>" class="card-footer-link text-warning">
								<i class="float-right fa fa-arrow-right"></i>
								View Details
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<h5 class="card-header">
					Chart Pendataan Buku
				</h5>
				<div class="card-body">
					<canvas id="chart" width="100%" height="40"></canvas>
				</div>
				<script type="text/javascript">
					$(function () {
						var color = Chart.helpers.color;
						var barChartData = {
							labels: [<?php 
							foreach ($data as $i => $item) {
								if ($i > 0) {echo ',';}
								if (isset($item->nama)) {
								echo '"' . $item->nama . '"';
							}else{
								echo '"nama tidak terdeteksi"';
								
							}
							} ?>],
							datasets: [<?php 
								$nomor = 1;
								$i_color = 0;
								$color = array('blue', 'red', 'yellow', 'green');
								foreach ($data_kolom as $key => $value) { 
									if ($nomor++ > 1) {echo ', ';}
								?>{
								label: '<?php echo $value ?>',
								backgroundColor: color(window.chartColors.<?php echo $color[$i_color]; ?>).alpha(0.5).rgbString(),
								borderColor: window.chartColors.<?php echo $color[$i_color++]; ?>,
								borderWidth: 1,
								data: [<?php 
									foreach ($data as $no => $item) {
										if ($no > 0) {echo ',';}
										echo '"'.$item->$key.'"';
									}
								 ?>]
							}<?php } ?>]

						};
						var ctx = document.getElementById("chart").getContext("2d");
						ctx.height = 40;
						window.myBar = new Chart(ctx, {
							type: 'bar',
							data: barChartData,
							options: {
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: false,
								},
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: true,
										}
									}]
								}
							}
						});
					});
				</script>
			</div>
			<div class="card mb-3">
				<h5 class="card-header">
					Jumlah Pendataan Buku Pertahun
				</h5>
				<table class="table table-striped table-hover mb-0">
					<thead>
						<tr>
							<th width="5">No</th>
							<?php 
							$colspan = 2;
							if (isset($data[0]->nama)): $colspan++; ?>
								<th>Bulan</th>
							<?php endif ?>
							<?php 
							$jumlah_data = array();
							foreach ($data_kolom as $key => $value): $jumlah_data[$key] = 0; ?>
								<th><?php echo $value; ?></th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $no => $item): ?>
							<tr>
								<td><?php echo $no + 1; ?></td>
								<td><?php echo $item->nama; ?></td>
								<?php foreach ($data_kolom as $key => $value): ?>
									<td><?php echo $item->$key; $jumlah_data[$key] += $item->$key; ?></td>
								<?php endforeach ?>
							</tr>
						<?php endforeach ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="<?php echo $colspan ?>">
								Total data: 
							</th>
							<?php foreach ($data_kolom as $key => $value): ?>
								<th><?php echo $jumlah_data[$key]; ?></th>
							<?php endforeach ?>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>