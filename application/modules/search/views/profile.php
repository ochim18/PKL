		<div class="page-title">
			<h2 class="text-center">Change Profile</h2>
		</div>
		<div class="page-content mb-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-7">
					<?php get_message_flash() ?>
					<form method="post" action="<?php url('user/profil') ?>">
						<div class="card">
							<div class="card-header">
								<h4 class="mt-1 mb-0">
									Form change profile
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
									<label class="col-sm-4 col-form-label">Nama Lengkap</label>
									<div class="col-sm-8">
										<input name="name" value="<?php echo $data->name; ?>" required="" type="text" class="form-control" placeholder="Nama Lengkap">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Username</label>
									<div class="col-sm-8">
										<input readonly="" value="<?php echo $data->username; ?>" required="" type="text" class="form-control" placeholder="Username">
									</div>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="checkbox" name="change_password" class="form-check-input" toggle-show="#change_password">
										Ubah Password
									</label>
								</div>
								<div class="hide" id="change_password">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Password Lama</label>
										<div class="col-sm-8">
											<input name="last_password" type="password" class="form-control" placeholder="Password">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Password Baru</label>
										<div class="col-sm-8">
											<input name="new_password" type="password" class="form-control" placeholder="Password">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Ulangi Password</label>
										<div class="col-sm-8">
											<input name="repeat_password" type="password" class="form-control" placeholder="Ulangi Password">
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-secondary pb-0 pt-3">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label"></label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-primary">Save Change</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>