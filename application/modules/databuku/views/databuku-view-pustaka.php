		<div class="page-title">
			<h2 class="text-center">Data Pustaka</h2>
			<form class="form-inline  justify-content-center mb-3">
					<a href="<?php url('databuku/export') ?>" class="btn btn-primary mr-2">
						<i class="fa fa-fw fa-cloud-download"></i> 
						Export data
					</a>
				<a href="<?php url('databuku/keluarga/input') ?>" class="btn btn-primary">
					<i class="fa fa-fw fa-plus"></i> 
					Tambah Pustaka
				</a>
			</form>
		</div>
		<div class="page-content">
        	<?php get_message_flash() ?>
			<div class="card">
				<div class="card-header">
					<h4 class="mt-1 mb-0">
						Data Pustaka
					</h4>
				</div>
				<div class="pt-3 pb-3">
					<table class="table table-striped table-hover datatable">
						<thead>
							<tr>
								<th width="5">No</th>
								<th>No ISSN</th>
								<th>Judul</th>
								<th>Kategori</th>
								<th>Penerbit</th>
								<th>Tahun</th>
								<th>create</th>
								<th>user</th>
								<th width="5">Aksi</th>
							</tr>
						</thead>
						<tbody>	
							<?php 
							$no_kartu = 0;
							foreach ($data_buku as $no => $item):$no_kartu++; ?>					
								<tr>											
									<td width="5"><?php echo $no + 1; ?></td>
									<td><?php echo $item->no_issn; ?></td>
									<td><?php echo $item->judul; ?></td>
									<td><?php echo $item->kategori_nama; ?></td>
									<td><?php echo $item->penerbit; ?></td>
									<td><?php echo $item->tahun_terbit; ?></td>
									<td><?php echo $item->created_at; ?></td>
									<td><?php echo $item->username; ?></td>
									<td class="text-nowrap text-center">
										<a href="<?php url('databuku/keluarga/view_pustaka_saya/'.$item->buku_id ) ?>"><i class="fa fa-fw fa-eye"></i></a> 
										<a href="<?php url('databuku/keluarga/edit/'.$item->buku_id ) ?>"><i class="fa fa-fw fa-pencil"></i></a> 
										<a onclick="return confirm('Apakah anda yakin akan menghapus data?')" href="<?php url('databuku/keluarga/delete/' . $item->buku_id ) ?>" class="text-danger"><i class="fa fa-fw fa-trash"></i></a>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
