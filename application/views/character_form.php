<html>
<?php $this->load->view('header');?>
<body>
    <div id="navbar" class="ui-widget-header nav-headbar"><?php $this->load->view('nav');?></div>
<div id="container">
<?=form_open('characters/add_new')?>
	<?=form_hidden('userID',$userID)?>
</div>
    <div id="footer" class="ui-widget-header"><?php $this->load->view('foot');?></div>
</body>
</html>
