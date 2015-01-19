<?php
$this->load->view($this->config->item('CL_header'));
?>

<p class="message"><?php echo $this->cl_auth->message; ?></p>

<h2>Welcome to the Example page</h2>

<p>This is a simple example to demonstrate the capabilities of CL Auth.</p><br />

<p>You can login with the following credentials:</p>

<p><strong>Username:</strong> Example</p>

<p><strong>Password:</strong> example</p><br />

<p>You can find more detailed <a href="http://www.jasonashdown.co.uk/cl_auth_doc/examples.php" target="_blank">examples online</a>.</p>

<?php
$this->load->view($this->config->item('CL_footer'));
?>