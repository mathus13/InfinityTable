<?php 
session_start();
$mysqli = mysqli_connect('192.168.1.59','webuser','','nurse_partners');
?>
<html>
	<head>
		<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	</head>
	<body>
		<form action="doc.php" method="post" name="doc_form">
			Title:<input type="text" name="title"> 
			Catagory:<select name="cat">
				<option value="MGH">MGH</option>
				<option value="Hurman Resources">Human Resources</option>
				<option value="Practice Information">Practice Information</option>
			</select>
			<input type="hidden" name="author" value="<?php echo $_SESSION['name']; ?>" /><br />
			<textarea name="memo" class="ckeditor"></textarea>
		</form>
	</body>
</html>