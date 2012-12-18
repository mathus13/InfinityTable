<?php
$nav = isset($nav)?$nav:'nav';
?>
<!doctype="html">
<html>
	<?php $this->load->view('includes/header');?>
	<body>
		<div id="container">
			<?php $this->load->view($nav);?>
			<div id="content">
				<?=$content?>
			</div>
			
		</div>
		<?php $this->load->view('includes/foot');?>
	</body>
</html>



