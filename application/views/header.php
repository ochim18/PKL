<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php echo (isset($title) ? $title . " &middot " : "") . get_option('app_name'); ?></title>

	<!-- Bootstrap Core CSS -->
	<link href="<?php url("assets/css/bootstrap.min.css") ?>" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?php url("assets/css/style.css") ?>" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="<?php url("assets/vendor/font-awesome/css/font-awesome.min.css") ?>" rel="stylesheet" type="text/css">
	<link href="<?php url("assets/vendor/dataTables.bootstrap4.min.css") ?>" rel="stylesheet">

	<!-- jQuery -->
	<script src="<?php url("assets/js/jquery-3.2.1.min.js") ?>"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?php url("assets/js/popper.min.js") ?>"></script>
	<script src="<?php url("assets/js/bootstrap.min.js") ?>"></script>

	<!-- Chartjs -->
	<script src="<?php url('assets/vendor/chartjs/chart.bundle.js'); ?>"></script>
	<script src="<?php url('assets/vendor/chartjs/utils.js'); ?>"></script>
	<script src="<?php url('assets/vendor/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?php url('assets/vendor/dataTables.bootstrap4.min.js'); ?>"></script>
	<script src="<?php url("assets/js/script.js") ?>"></script>
	<script src="<?php url("assets/jquery-ui.css") ?>"></script>
	<script src="<?php url("assets/jquery-ui.js") ?>"></script>
	<script>
		$(document).ready(function() {
			$('.datatable').DataTable({
				columnDefs: [
					{ orderable: false, targets: -1 }
				]
			});
		} );
	</script>

	<script>
            $(document).ready(function(){
            $('#myDropDown').change(function(){
                //Selected value
                var inputValue = $(this).val();
                alert("value in js "+inputValue);

                //Ajax for calling php function
                $.post('submit.php', { dropdownValue: inputValue }, function(data){
                    alert('ajax completed. Response:  '+data);
                    //do after submission operation in DOM
                });
            });
        });
    </script>
</head>
<body class="active-side-nav">
<header class="clearfix">
	<nav class="navbar navbar-expand-lg fixed-top navbar-dark">
		<a class="navbar-toggler" aria-label="Toggle navigation" side-nav-toggle>
			<span class="navbar-toggler-icon"></span>
		</a>
		<a class="navbar-brand" href="<?php url()?>"><?php echo get_option('app_name') ?></a>

		<div class="float-right">
			<ul class="navbar-nav">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle text-nowrap" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="icon-menu avatar"><?php echo ucwords(substr(current_user_data('name'), 0, 1))?></div>
						&nbsp;
						<span> <?php echo ucwords(current_user_data('name')) ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="<?php url("dasbor")?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?php url("user/profil")?>"><i class="fa fa-user fa-fw"></i> Profile</a>
						<a class="dropdown-item" href="<?php url("user/logout")?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>

		<ul class="side-nav">
			<?php foreach (get_app_config('main_menu_' . current_user_data('capability')) as $menu): ?>
				<?php if ($menu == 'diveder'): ?>
					<div class="dropdown-divider"></div>
				<?php else: ?>
					<li class="nav-item <?php if(isset($active_menu) && $active_menu == $menu['id']) echo "active"; ?>">
						<a class="nav-link" href="<?php url($menu['url']) ?>">
							<i class="fa <?php echo $menu['icon']; ?> fa-fw"></i> 
							<?php echo $menu['label']; ?>
						</a>
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</nav>
</header>
	<div class="body">
		<div class="container-fluid">