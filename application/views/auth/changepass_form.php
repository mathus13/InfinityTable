<?php

$this->load->view($this->config->item('CL_header'));

$form = $this->validation;

$old_pass = array(
'name'	=> 'old_pass',
'id'	=> 'old_pass',
'maxlength'	=> 25,
'size'	=> 30,
'value' => isset($form->old_pass) ? $form->old_pass : '');

$new_pass = array(
'name'	=> 'new_pass',
'id'	=> 'new_pass',
'maxlength'	=> 25,
'size'	=> 30);

$confirm_new_pass = array(
'name'	=> 'confirm_pass',
'id'	=> 'confirm_pass',
'maxlength' => 25,
'size' => 30);

?>

<fieldset>
<legend>Chnage Password</legend>
<?php echo form_open('auth/changepassword')?>

<?php
if ( $this->cl_auth->user_error ): ?>
<p><?php echo $this->cl_auth->user_error; ?></p>
<?php
endif;
?>

<dl>
	<dt><?php echo form_label('Old Password', $old_pass['id'])?></dt>
	<dd><?php echo form_password($old_pass)?>
	<?php echo $form->old_pass_error;?></dd>

	<dt><?php echo form_label('New Password', $new_pass['id'])?></dt>
	<dd><?php echo form_password($new_pass)?>
	<?php echo $form->new_pass_error;?></dd>

	<dt><?php echo form_label('Confirm New Password', $confirm_new_pass['id'])?></dt>
	<dd><?php echo form_password($confirm_new_pass)?>
	<?php echo $form->confirm_pass_error;?></dd>

	<dt></dt>
	<dd><?php echo form_submit(false, 'Change Password');?></dd>
</dl>

<?php echo form_close()?>
</fieldset>

<?php
$this->load->view($this->config->item('CL_footer'));
?>