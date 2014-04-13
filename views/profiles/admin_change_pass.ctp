<script>
$(function(){
	$("label").hide();
	
	$("#ProfileChangePassForm").submit(function() {
		if($('#ProfileNewPass').val() == "" || $('#ProfileConfPass').val() == ""){
			alert('Debe llenar TODOS los campos');
			return false;
		}
		
		if($('#ProfileNewPass').val() != $('#ProfileConfPass').val()){
			alert('El password debe ser igual en ambos campos');
			return false;
		}
    });
});
</script>
<div class="profiles form">
<?php echo $form->create('Profile',array('action'=>'change_pass'));?>
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td>Escriba nuevo password:</td>
			<td><?php echo $form->input('new_pass',array('type'=>'password')) ?></td>
		</tr>
		<tr>
			<td>Confirme nuevo password:</td>
			<td><?php echo $form->input('conf_pass',array('type'=>'password')) ?></td>
		</tr>
	</table>
	<?php
		echo $form->input('user_id',array('value'=>$user_id,'type'=>'hidden'));
	?>
<?php echo $form->end('Submit');?>
</div>