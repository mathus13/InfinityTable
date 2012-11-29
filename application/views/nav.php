
<nav style="width:100%;" class="ui-widget-header nav-headbar">
	<ul>
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'"><?=anchor('welcome','Home')?></li>
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'" id="log-msg">Log</li>
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'"><a href="#" id="about-link">about</a></li> 
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'"><a href="#" id="faq-link">faq</a></li> 
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'"><a href="#" id="wiki-link">wiki</a></li> 
		<li class="'.($current_url='welcome'?'ui-state-active':'ui-state-default').'"><a href="logout.php" id="logout-link">logout</a></li>
	</ul> 
</nav>
