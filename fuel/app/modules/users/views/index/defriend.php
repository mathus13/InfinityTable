<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "list" ); ?>'><?php echo Html::anchor('index/list','List');?></li>
	<li class='<?php echo Arr::get($subnav, "edit" ); ?>'><?php echo Html::anchor('index/edit','Edit');?></li>
	<li class='<?php echo Arr::get($subnav, "add" ); ?>'><?php echo Html::anchor('index/add','Add');?></li>
	<li class='<?php echo Arr::get($subnav, "delete" ); ?>'><?php echo Html::anchor('index/delete','Delete');?></li>
	<li class='<?php echo Arr::get($subnav, "friend" ); ?>'><?php echo Html::anchor('index/friend','Friend');?></li>
	<li class='<?php echo Arr::get($subnav, "defriend" ); ?>'><?php echo Html::anchor('index/defriend','Defriend');?></li>

</ul>
<p>Defriend</p>