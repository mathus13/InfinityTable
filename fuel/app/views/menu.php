<ul class="nav navbar-nav navbar-inverse">
<?php foreach($subnav['links'] as $link):?>
	<li class="<?=Arr::get($link,'class') ?> <?=(Arr::get($item,$link['id']))? 'active' : ''?>">
		<?=Html::anchor($link['url'],$link['text']);?>
	</li>
<?php endforeach;?>
</ul>