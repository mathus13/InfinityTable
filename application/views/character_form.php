<style>
div.field{
	width:30%;
	display:inline-block;
}
</style>
<div id="container">
<?=form_open('characters/add_new')?>
	<?=form_hidden('user_id',$user_id)?>
	<?=form_fieldset('Details')?>
		<div class="field"><?=form_label('Name','name').form_input('name')?></div>
		<div class="field"><?=form_label('Class','class').form_input('class')?></div>
		<div class="field add"><a href="" class="addField" data-fieldset="Details">Add Field</a></div>
	<?=form_fieldset_close()?>
	<?=form_fieldset('Attributes')?>
		<div class="field add"><a href="" class="addField" data-fieldset="Details">Add Field</a></div>
	<?=form_fieldset_close()?>
	<?=form_fieldset('Skills')?>
		<div class="field add"><a href="" class="addField" data-fieldset="Details">Add Field</a></div>
	<?=form_fieldset_close()?>
	<?=form_fieldset('Special')?>
		<div class="field add"><a href="" class="addField" data-fieldset="Details">Add Field</a></div>
	<?=form_fieldset_close()?>
	<?=form_label('Upload An Image','image').form_upload('image')?><br/>
	<?=form_submit('submit','Enter').form_close()?>
<?=form_close()?>
</div>
<script>
$('a.addfield').button().css('font-size','10');
$('input[name="submit"]').button().css('font-size','12');
</script>