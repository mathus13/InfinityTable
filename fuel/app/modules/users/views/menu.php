<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "list" ); ?>'><?php echo Html::anchor('users/list','List');?></li>
	<li class='<?php echo Arr::get($subnav, "edit" ); ?>'><?php echo Html::anchor('users/edit','Edit');?></li>
	<li class='<?php echo Arr::get($subnav, "add" ); ?>'><?php echo Html::anchor('users/add','Add');?></li>
	<li class='<?php echo Arr::get($subnav, "delete" ); ?>'><?php echo Html::anchor('users/delete','Delete');?></li>
	<li class='<?php echo Arr::get($subnav, "friend" ); ?>'><?php echo Html::anchor('users/friend','Friend');?></li>
	<li class='<?php echo Arr::get($subnav, "defriend" ); ?>'><?php echo Html::anchor('users/defriend','Defriend');?></li>

</ul>