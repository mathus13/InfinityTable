
<nav class="ui-corner-all ui-state-default nav-headbar">
	<div style="margin:0 auto">
		<ul>
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>">
				<?=anchor('welcome','Home')?>
			</li>
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>" id="log-msg">
				Log
			</li>
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>">
				<a href="#" id="about-link">about</a>
			</li> 
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>">
				<a href="#" id="faq-link">faq</a>
			</li> 
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>">
				<a href="#" id="wiki-link">wiki</a>
			</li> 
			<li class="<?=($current_url='welcome'?'ui-state-active':'ui-state-default')?>">
				<a href="logout.php" id="logout-link">logout</a>
			</li>
		</ul> 
	</div>
</nav>
