
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
						<div class="card">
							<div class="row">
								<div class="col-sm-6 border border-top-0 border-bottom-0 border-left-0 pr-0">
									<div class="card-header">
										<h4 class="mt-1 mb-0">
											<a href="<?php url("databuku") ?>" class="text-muted"><i class="fa fa-fw fa-arrow-left"></i></a>
											View Data Pustaka
										</h4>
									</div>
									<div class="card-body pb-0">
										<div class="form-group row">
											<label class="col-sm-3"><b>No ISSN</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['no_issn'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Judul</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['judul'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>kategori</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['kategori_nama'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Jenis</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['jenis_nama'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Pengarang</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['pengarang'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Tahun Terbit</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['tahun_terbit'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Penerbit</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['penerbit'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Dibuat Tgl</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['tahun'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>User</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['username'] ?></label>
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
											<label class="col-sm-3 "><b>Keyword</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['keyword'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>Abstract</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<label><?php echo $post['abstract'] ?></label>
											</div>
										</div><hr>
										<div class="form-group row">
											<label class="col-sm-3 "><b>File</b></label>
											<label class="col-sm-1"><b>:</b></label>
											<div class="col-sm-8">
												<a id="trigger" type="button" class="btn btn-warning">Open Document</a>
			                                    <div id="dialog" style="display:none;">
			                                        <div>
			                                            <embed id="nocopy" src="<?php url("uploads"); echo '/'.$post['data'] ?>#toolbar=0" width="800px" height="550px" />
			                                        </div>
			                                    </div> 
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-secondary pt-3 mb-1 text-right">
								<?php if (current_user_data('capability') == 'admin'): ?>
									<a href="<?php url('databuku/keluarga/input') ?>" class="btn btn-secondary mr-2">
											<i class="fa fa-fw fa-plus"></i> 
											Tambah Keluarga
									</a>
								<?php endif ?>
								<?php if ($post['user_id'] == current_user_data('user_id')): ?>
									<a href="<?php url('databuku/keluarga/edit/').$post['buku_id'] ?>" class="btn btn-primary">
										<i class="fa fa-fw fa-pencil"></i> 
										Edit Pustaka
									</a>
								<?php endif ?>
							</div>
						</div>
				</div>
			</div>
		</div>
		<script language="javascript" type="text/javascript">
		  $(document).ready(function() {
		    $('#trigger').click(function(){
		      $("#dialog").dialog();
		    }); 
		  });                  
		</script>