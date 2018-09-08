		<div class="page-title text-center">
			<h2><?php echo $title; ?></h2>
		</div>
		<div class="content row justify-content-center mb-3">
			<div class="col-md-6">
				<?php get_message_flash() ?>
				<form enctype="multipart/form-data" method="post" action="<?php url("seting") ?>">
					<div class="card mb-3">
						<h4 class="card-header">
							Form <?php echo $title; ?>
						</h4>
						<?php if( isset($errors) ){ ?>
							<div class="alert alert-danger rounded-0 mb-0">
				                <button type="button" class="close" data-dismiss="alert">
				                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				                </button>
								<?php foreach ($errors as $error) echo $error.'<br>'; ?>
							</div>
						<?php } ?>
						<div class="card-body pb-0">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Nama Apl</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="option[app_name]" value="<?php echo get_option('app_name') ?>" placeholder="Nama Apl" required="">
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Nama Lembaga</label>
								<div class="col-sm-8">
									<textarea class="form-control" name="option[lembaga_name]" placeholder="Nama Lembaga" required=""><?php echo get_option('lembaga_name') ?></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Logo Lembaga</label>
								<div class="col-sm-8">
									<input type="file" class="form-control" name="logo" accept=".png,.jpg,.jpeg,.gif">
									<?php if ($logo = get_option('logo')): ?>
										<img class="mt-2" src="<?php url('uploads/'.$logo) ?>" style="max-width: 100%; height: auto;">
									<?php endif ?>
								</div>
							</div>
						</div>
						<div class="card-footer pb-0 pt-3">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"></label>
								<div class="col-sm-8">
									<button class="btn btn-primary">Simpan Perubahan</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>