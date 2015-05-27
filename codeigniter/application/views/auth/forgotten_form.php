<?php

$this->load->view($this->config->item('CL_header'));

$form = $this->validation;

$login = array(
'name'	=> 'login',
'id'	=> 'login',
'maxlength'	=> 80,
'size'	=> 30,
'value' => isset($form->login) ? $form->login : '');

?>

<fieldset><legend accesskey="D" tabindex="1">Forgotten Password</legend>
<?php echo form_open('auth/forgotten')?>

<?php
if ( $this->cl_auth->user_error ): ?>
<p class="error"><?php echo $this->cl_auth->user_error; ?></p>
<?php
endif;
?>

<dl>
	<!--USERNAME-->
	<dt><?php echo form_label('Enter your Username or Email Address', $login['id']);?></dt>
	<dd><?php echo form_input($login);?> <?php echo form_submit(false, 'Reset Now');?>
    <?php echo $form->login_error;?></dd>
</dl>

<?php echo form_close()?>
</fieldset>

<?php
$this->load->view($this->config->item('CL_footer'));
?>