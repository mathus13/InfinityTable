$('document').ready(function(){
	$('#container').tabs();
	$('#about-link').click(function(){
		$('#display').load('welcome/about/');
	});
});
