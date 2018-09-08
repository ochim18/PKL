		<div class="page-title">
			<h2 class="text-center">Master Administrator</h2>
		</div>
		<div class="page-content">
			<div class="row d-flex justify-content-center">
				<div class="col-md-7">
					<?php get_message_flash() ?>
					<form method="post" action="<?php url('admin/administrator/' . ($mode == "add" ? "input" : "edit/" . $post['user_id'])) ?>">
						<div class="card">
							<div class="card-header">
								<?php if ($mode == 'edit'): ?>
									<div class="float-right">
										<a href="<?php url('admin/administrator/input') ?>" class="btn btn-secondary btn-sm">
											<i class="fa fa-fw fa-plus"></i> 
											Tambah Administrator
										</a>
									</div>
								<?php endif ?>
								<h4 class="mt-1 mb-0">
									<a href="<?php url("admin/administrator") ?>" class="text-muted"><i class="fa fa-fw fa-arrow-left"></i></a>
									<?php echo ($mode == "add" ? "Input" : "Edit"); ?> Administrator
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
										<input name="name" <?php if(isset($post['name'])) echo 'value="' . $post['name'] . '"'; ?> required="" type="text" class="form-control" placeholder="Nama Lengkap">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Username</label>
									<div class="col-sm-8">
										<input name="username" <?php if(isset($post['username'])) echo 'value="' . $post['username'] . '"'; ?> required="" type="text" class="form-control" placeholder="Username">
									</div>
								</div>
								<?php if ($mode == "edit"): ?>
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" name="change_password" class="form-check-input" toggle-show="#change_password">
											Ubah Password
										</label>
									</div>
								<?php endif ?>
								<div <?php if ($mode == "edit") echo 'class="hide"'; ?> id="change_password">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Password</label>
										<div class="col-sm-8">
											<input name="password" type="password" class="form-control" placeholder="Password">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Ulangi Password</label>
										<div class="col-sm-8">
											<input name="repeat_password" type="password" class="form-control" placeholder="Ulangi Password">
										</div>
									</div>
								</div>
								<?php if ($mode == "edit"): ?>
									<hr>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Terakhir Login</label>
										<label class="col-sm-8 col-form-label"><?php echo datetime_html($post['last_login']); ?> <span class="badge badge-secondary"><?php echo $post['login_count']; ?></span></label>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Tanggal Dibuat</label>
										<label class="col-sm-8 col-form-label"><?php echo datetime_html($post['created_at']); ?></label>
									</div>
								<?php endif ?>
							</div>
							<div class="card-footer text-secondary pb-0 pt-3">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label"></label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-primary"><?php echo ($mode == "add" ? "Input" : "Edit"); ?> Administrator</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	