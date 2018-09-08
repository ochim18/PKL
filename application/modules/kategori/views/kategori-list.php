		<div class="page-title">
			<h2 class="text-center">Master kategori</h2>
		</div>
		<div class="page-content mb-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-6">
					<?php get_message_flash() ?>
					<div class="card">
						<div class="card-header">
							<div class="float-right">
								<a href="<?php url('kategori/input') ?>" class="btn btn-primary btn-sm">
									<i class="fa fa-fw fa-plus"></i> 
									Tambah Kategori
								</a>
							</div>
							<h4 class="mt-1 mb-0">
								kategori Buku
							</h4>
						</div>
						<table class="table table-hover mb-0">
							<thead>
								<tr>
									<th width="5">No</th>
									<th>Kategori</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$rowspan_provinsi = 1;
								$rowspan_jenis = 1;
								$rowspan_kecamatan = 1;
								$provinsi = 0;
								$jenis = 0;
								foreach ($data1 as $no => $item): ?>
									<tr>										
										<td width="5"><?php echo $no + 1; ?></td>
										<?php $provinsi++; ?>
											<td>
												<div class="float-right dropdown">
													<a href="<?php url('kategori/edit/' . $item->kategori_id ) ?>" title="Edit Kategori"><i class="fa fa-fw fa-pencil"></i></a> 
													<?php if ($item->jumlah == 0): ?>
														<a onclick="return confirm('Apakah anda yakin akan menghapus data?')" href="<?php url('kategori/delete/' . $item->kategori_id ) ?>" class="text-danger"><i class="fa fa-fw fa-trash"></i></a>
													<?php endif ?>
														<a href="#" data-toggle="dropdown" title="Lihat Jenis"><i class="fa fa-fw fa-caret-right "></i></a>
														<ul class="dropdown-menu" style="padding-left: 20px; padding-right: 10px;">
															<li style="width: 200px;">
															    	<b><?php echo "Jenis ".$item->kategori_nama; ?></b>
															    	<div class="float-right">
																		<a href="<?php url('kategori/jenis/input/' . $item->kategori_id ) ?>" title="Tambah jenis"><i class="fa fa-fw fa-plus"></i></a> 
																	</div>
															    </li><hr style="margin-top: 2px; margin-bottom: 2px; ">
															    <?php if($item->jumlah > 0): ?>
														<?php foreach ($data as $key => $value): 
															if($value->kategori_id == $item->kategori_id):$jenis++;
														?>
														    <li style="width: 200px;">
														    	<?php echo $value->jenis_nama; ?>
														    	<div class="float-right">
																	<a href="<?php url('kategori/jenis/edit/' . $value->jenis_id ) ?>"><i class="fa fa-fw fa-pencil"></i></a>  
																	<a onclick="return confirm('Apakah anda yakin akan menghapus data?')" href="<?php url('kategori/jenis/delete/' . $value->jenis_id ) ?>" class="text-danger"><i class="fa fa-fw fa-trash"></i></a>
																</div>
														    </li><hr style="margin-top: 2px; margin-bottom: 2px; ">
														    <?php endif ?>
															<?php endforeach ?>
															<?php endif ?>
														</ul>
												</div>
												<?php echo $item->kategori_nama; ?>
											</td>
									</tr>
								<?php endforeach ?>
							</tbody>
							</table>
						<div class="card-footer text-secondary">
							Jumlah: <?php echo $provinsi ?> Kategori,  <?php echo $jenis ?> Jenis</div>
					</div>
				</div>
			</div>
		</div>