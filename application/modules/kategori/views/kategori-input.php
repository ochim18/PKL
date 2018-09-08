		<div class="page-title">
			<h2 class="text-center">Master Kategori</h2>
		</div>
		<div class="page-content mb-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-7">
					<?php get_message_flash() ?>
					<form method="post" action="<?php url('kategori/' . ($mode == "add" ? "input" : "edit/" . $post['kategori_id'])) ?>">
						<div class="card">
							<div class="card-header">
								<?php if ($mode == 'edit'): ?>
									<div class="float-right">
										<a href="<?php url('kategori/input') ?>" class="btn btn-secondary btn-sm">
											<i class="fa fa-fw fa-plus"></i> 
											Tambah Kategori
										</a>
									</div>
								<?php endif ?>
								<h4 class="mt-1 mb-0">
									<a href="<?php url("kategori") ?>" class="text-muted"><i class="fa fa-fw fa-arrow-left"></i></a>
									<?php echo ($mode == "add" ? "Input" : "Edit"); ?> Kategori
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
									<label class="col-sm-4 col-form-label">Nama Kategori</label>
									<div class="col-sm-8">
										<input name="kategori_nama" <?php if(isset($post['kategori_nama'])) echo 'value="' . $post['kategori_nama'] . '"'; ?> required="" type="text" class="form-control" placeholder="Nama Kategori">
									</div>
								</div>
							</div>
							<div class="card-footer text-secondary pb-0 pt-3">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label"></label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-primary"><?php echo ($mode == "add" ? "Input" : "Edit"); ?> Kategori</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>