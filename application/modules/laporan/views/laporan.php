		<div class="page-title">
			<h2 class="text-center">Laporan Data Buku</h2> 
			<div class="justify-content-center">
				<ul class="nav nav-pills justify-content-center mb-2">
					<li class="nav-item mr-2">
						<a class="nav-link active" href="<?php url('laporan/export/'.$active_wilayah )  ?>"><i class="fa fa-fw fa-cloud-download"></i>  Export Data</a>
					</li>
				</ul>
				<ul class="nav nav-pills justify-content-center mb-2">
					<li class="nav-item mr-1">
						<?php
							// Sets the top option to be the current year. (IE. the option that is chosen by default).
							 $currently_selected = $tahun;
							// Year to start available options at
							$earliest_year = date('Y-m-d', strtotime('-10 years')); 
							// Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
							$latest_year = date('Y', strtotime('+1 years')); 
							print '<select onchange="location = this.value" class="form-control">';
							// Loops over each int[year] from current year, back to the $earliest_year [1950]
							foreach ( range( $latest_year, $earliest_year ) as $i ) {
							// Prints the option with the next year in range.
								if($i == date('Y', strtotime('+1 years'))) $i = 'All';
								echo '<option value="';
								$link = url('laporan/view/'.$active_wilayah.'/'.$i);
								if($i == $currently_selected){
									echo $link.'"'.' selected="selected"'.' id="'.$link.'">'.$i.'</option>';
								}else{
									echo $link.'"'.' id="'.$link.'">'.$i.'</option>';
								}
							}
							print '</select>';
						?>
					</li>
					<?php foreach ($wilayah as $key => $value): ?>
					<li class="nav-item mr-1">
						<a class="nav-link <?php echo ($key == $active_wilayah ? 'active' : 'bg-light') ?>" href="<?php url('laporan/view/' . $key.'/'.$currently_selected) ?>"><?php echo $value; ?></a>
					</li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="page-content">
        	<?php get_message_flash() ?>
			<div class="card mb-3">
				<div class="card-header">
					<h4 class="mt-1 mb-0">
						Grafik Data
					</h4>
				</div>
				<div class="card-body">
					<div class="row justify-content-center">
						<div class="col-sm-12">
							<canvas id="chart" width="100%" height="40"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="mt-1 mb-0">
						Data Jumlah
					</h4>
					<table class="table table-striped table-hover mb-0">
					<thead>
						<tr>
							<th width="5">No</th>
							<?php 
							$colspan = 2;
							if (isset($data[0]->nama)): $colspan++; ?>
								<th><?php echo $active_wilayah ?></th>
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