<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<style>
		body { margin: 40px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="col-md-12">
			<h1><?php echo $title; ?></h1>
			<hr>
<?php if (Session::get_flash('success')): ?>
			<div class="alert alert-success">
				<strong>Success</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
				</p>
			</div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
			<div class="alert alert-danger">
				<strong>Error</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
				</p>
			</div>
<?php endif; ?>
		</div>
		<div class="col-md-12">
<?php echo View::forge('menu',array('subnav'=>$data['subnav'])); ?>
<?php echo $content; ?>
		</div>
		<footer>
			<p class="pull-right">
				<small>Page rendered in {exec_time}s using {mem_usage}mb of memory.</small><br>
				<small>Fuel Version: <?php echo e(Fuel::VERSION); ?></small>
			</p>
			<p>
				An <a href="etherealvisions.us" target="_blank">Ethereal Development</a> Project
			</p>
		</footer>
	</div>
</body>
</html>
