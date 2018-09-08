
		<div class="page-title">
			<h2 class="text-center">Data Pustaka</h2>
			<?php if (isset($errors)): ?>
				<div class="alert alert-danger mb-0">
					<button type="button" class="close" data-dismiss="alert">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<?php foreach ($errors as $error): ?>
						<p class="mb-0"><?php echo $error; ?></p>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
		<div class="page-content mb-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-12">
					<?php get_message_flash() ?>
					<form method="post" action="<?php url('databuku/keluarga/' . ($mode == "add" ? "input" : "edit/" . $post["buku_id"])) ?>" enctype="multipart/form-data">
						<div class="card">
							<div class="row">
								<div class="col-sm-6 border border-top-0 border-bottom-0 border-left-0 pr-0">
									<div class="card-header">
										<h4 class="mt-1 mb-0">
											<a href="<?php url("databuku") ?>" class="text-muted"><i class="fa fa-fw fa-arrow-left"></i></a>
											<?php echo ($mode == "add" ? "Input" : "Edit"); ?> Data Pustaka
										</h4>
									</div>
									<div class="card-body pb-0">
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">No ISSN</label>
											<div class="col-sm-8">
												<input name="no_issn" <?php if(isset($post['no_issn'])) echo 'value="' . $post['no_issn'] . '"'; ?> required="" type="text" class="form-control" placeholder="No ISSN">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Judul</label>
											<div class="col-sm-8">
												<input name="judul" <?php if(isset($post['judul'])) echo 'value="' . $post['judul'] . '"'; ?> required="" type="text" class="form-control" placeholder="Judul">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">kategori</label>
											<div class="col-sm-8">
												<select id="kategori" name="kategori_id" required="" class="form-control">
														<option>Pilih Kategori</option>
													<?php foreach ($data_kategori as $value): ?>
														<option <?php if (isset($post['kategori_id']) && $post['kategori_id'] == $value->kategori_id) echo 'selected=""'; ?> value="<?php echo $value->kategori_id; ?>"><?php echo $value->kategori_nama; ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Jenis</label>
											<div class="col-sm-8">
												<select id="jenis" name="jenis" required="" class="form-control" >
													<option>Pilih Jenis</option>
													<?php if (isset($post['jenis_id'])):?>
														<option selected="" value="<?php echo $post['jenis_id']; ?>"><?php echo $post['jenis_nama']; ?></option>
													<?php endif ?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Pengarang</label>
											<div class="col-sm-8">
												<textarea rows="3" placeholder="Pengarang" class="form-control" name="pengarang" required=""><?php if(isset($post['pengarang'])) echo htmlspecialchars($post['pengarang']);?></textarea>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Tahun Terbit</label>
											<div class="col-sm-8">
											<?php
												  // Sets the top option to be the current year. (IE. the option that is chosen by default).
												  $currently_selected = 0; 
												  if (isset($post['tahun_terbit'])){
												  	$currently_selected = $post['tahun_terbit'];
												  }
												  // Year to start available options at
												  $earliest_year = date('Y-m-d', strtotime('-10 years')); 
												  // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
												  $latest_year = date('Y'); 
												  

												  print '<select name="tahun_terbit" required="" class="form-control">';
												  // Loops over each int[year] from current year, back to the $earliest_year [1950]
												  foreach ( range( $latest_year, $earliest_year ) as $i ) {
												    // Prints the option with the next year in range.
														if($i == $currently_selected){
														    print '<option value="'.$i.'"'.' selected="selected"'.'>'.$i.'</option>';

														}else{
															print '<option value="'.$i.'"'.'>'.$i.'</option>';
														}
												  }
												  print '</select>';
												?>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Penerbit</label>
											<div class="col-sm-8">
												<input name="penerbit" <?php if(isset($post['penerbit'])) echo 'value="' . $post['penerbit'] . '"'; ?> required="" type="text" class="form-control" placeholder="Penerbit">
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 pl-0">
									<div class="card-header">
										<h4 class="mt-1 mb-0">
											Keterangan
										</h4>
									</div>
									<div class="card-body pb-0">
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Keyword</label>
											<div class="col-sm-8">
												<textarea rows="3" placeholder="keyword" class="form-control" name="keyword" required=""><?php if(isset($post['keyword'])) echo $post['keyword']?></textarea>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Abstract</label>
											<div class="col-sm-8">
												<textarea rows="12" placeholder="abstract" class="form-control" name="abstract" required=""><?php if(isset($post['abstract'])) echo htmlspecialchars($post['abstract'])?></textarea>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">File</label>
											<div class="col-sm-8">
												<input type="file" class="form-control" name="data" accept=".pdf">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-secondary pt-3 mb-1 text-right">
								<?php if ($mode == 'edit'): ?>
									<a href="<?php url('databuku/keluarga/input') ?>" class="btn btn-secondary mr-2">
										<i class="fa fa-fw fa-plus"></i> 
										Tambah Keluarga
									</a>
								<?php endif ?>
								<button type="submit" class="btn btn-primary"><?php echo ($mode == "add" ? "Input" : "Edit"); ?> Data Anggota Keluarga</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function(){
				$('#kategori').on('change', function(){
					var kategori_id = $(this).val();
					if(kategori_id == ''){
						$('#jenis').prop('disabled',true);
					}else{
						$('#jenis').prop('disabled',false);
						$.ajax({
							url:"<?php echo base_url('databuku/keluarga/take_data')?>",
							type:"POST",
							data: {'kategori_id':kategori_id},
							datatype: 'json',
							success: function(data){
								$('#jenis').html(data);
							},
							error: function(){
								alert('Error occurs...!!');
							}
						})
					}
				});
			});
		</script>
		<script>
			$(document).ready(function(){
				doit();
			});


				function doit(){
					var kategori_id = <?php echo(json_encode($post['kategori_id'])); ?>;
					var jenis_id = <?php echo(json_encode($post['jenis_id'])); ?>;
					if(kategori_id == ''){
						$('#jenis').prop('disabled',true);
					}else{
						$('#jenis').prop('disabled',false);
						$.ajax({
							url:"<?php echo base_url('databuku/keluarga/take_data/')?>",
							type:"POST",
							data: {'kategori_id':kategori_id, 'jenis_id':jenis_id},
							datatype: 'json',
							success: function(data){
								$('#jenis').html(data);
							},
							error: function(){
								alert('Error occurs...!!');
							}
						})
					}
				};
		</script>