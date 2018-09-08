<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">


	<!-- Bootstrap Core CSS -->
	<link href="<?php url("assets/css/bootstrap1.min.css") ?>" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?php url("assets/css/style.css") ?>" rel="stylesheet">
	<link href="<?php url("assets/css/style1.css") ?>" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="<?php url("assets/vendor/font-awesome/css/font-awesome.min.css") ?>" rel="stylesheet" type="text/css">

	<!-- jQuery -->
	<script src="<?php url("assets/js/jquery-3.2.1.min.js") ?>"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?php url("assets/js/popper.min.js") ?>"></script>
	<script src="<?php url("assets/js/bootstrap1.min.js") ?>"></script>

	<script src="<?php url("assets/js/script.js") ?>"></script>
	<script src="<?php url("assets/jquery-ui.css") ?>"></script>
	<script src="<?php url("assets/jquery-ui.js") ?>"></script>


	<title><?php echo (isset($title) ? $title . " &middot " : "") . get_option('app_name'); ?></title>
	<script type="text/javascript">
		document.onmousedown = disableRightclick;
		var message = "Right click not allowed !!";
		function disableRightclick(evt){
		    if(evt.button == 2){
		        alert(message);
		        return false;    
		    }
		}
	</script>
	<script language="javascript" type="text/javascript">
		  $(document).ready(function() {
		    $('#trigger').click(function(){
		      $("#dialog").dialog();
		    }); 
		  });                  
	</script>

</head>
<body oncopy="return false" oncut="return false" onpaste="return false">
	<div class="container">