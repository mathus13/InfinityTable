<html>
<?php $this->load->view('header');?>
<body>
    <div id="navbar" class="ui-widget-header nav-headbar"><?php $this->load->view('nav');?></div>
<div id="container">
	<ul>
		<li><a href="#profile">Profile</a></li>
		<li><a href="#characters">Characters</a></li>
		<li><a href="#groups">Groups / Chronicals</a></li>
	</ul>
    <div id="profile">
	Username: <?=$username?>
    </div>
    <div id="characters">
	<?php if(count($characters) < 1):?>
		You have not made any characters yet! <br/>
		Make one <a href="<?=site_url('character/start')?>" class="makeCharacter">here</a>
	<?php else:?>
		<ul>
		<?php foreach($characters as $character):?>
			<li><?=$character['name']?></li>
		<?php endforeach;?>
		</ul>
	<?php endif;?>
    </div>
    <div id="groups">
	Coming Soon: Groups!
	</div>
</div>
    <div id="footer" class="ui-widget-header"><?php $this->load->view('foot');?></div>
</body>
</html>