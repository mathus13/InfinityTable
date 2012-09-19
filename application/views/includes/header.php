<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title><?=$title?></title>
	<?php foreach($head['css'] as $css):
		echo link_tag(base_url().'assets/css/'.$css);
	endforeach;?>
	<?php foreach($head['js'] as $js):?>
	<script src="<?=base_url().'assets/js/'.$js?>"></script>
	<?php endforeach;?>
</head>
