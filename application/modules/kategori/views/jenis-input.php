  		<div class="page-title">
			<h2 class="text-center">Master Kategori</h2>
		</div>
		<div class="page-content mb-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-7">
					<?php get_message_flash() ?>
					<form method="post" action="<?php url('kategori/jenis/input/' . ($mode == "add" ? "input" : "edit/" . $post['jenis_id'])) ?>">
						<div class="card">
							<div class="card-header">
								<?php if ($mode == 'edit'): ?>
									<div class="float-right">
										<a href="<?php url('kategori/jenis/input') ?>" class="btn btn-secondary btn-sm">
											<i class="fa fa-fw fa-plus"></i> 
											Tambah Jenis
										</a>
									</div>
								<?php endif ?>
								<h4 class="mt-1 mb-0">
									<a href="<?php url("kategori") ?>" class="text-muted"><i class="fa fa-fw fa-arrow-left"></i></a>
									<?php echo ($mode == "add" ? "Input" : "Edit"); ?> Jenis
								</h4>
							</div>
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
							<div class="card-body pb-0">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Kategori</label>
									<div class="col-sm-8">
										<select class="form-control" name="kategori_id">
											<?php foreach ($data_kategori as $item): 
												if (isset($id) && $id == $item->kategori_id || isset($post['kategori_id']) && $post['kategori_id'] == $item->kategori_id) {
													$selected = true;
												}else{
													$selected = false;
												}
												?>
												<option value="<?php echo $item->kategori_id ?>" <?php if ($selected) echo 'selected=""'; ?>><?php echo $item->kategori_nama ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Nama Jenis</label>
									<div class="col-sm-8">
										<input name="jenis_nama" <?php if(isset($post['jenis_nama'])) echo 'value="' . $post['jenis_nama'] . '"'; ?> required="" type="text" class="form-control" placeholder="Nama Jenis">
									</div>
								</div>
							</div>
							<div class="card-footer text-secondary pb-0 pt-3">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label"></label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-primary"><?php echo ($mode == "add" ? "Input" : "Edit"); ?> Jenis</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>