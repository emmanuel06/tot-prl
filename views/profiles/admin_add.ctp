<script>
$(function(){
	$("#ProfileAddForm").submit(function() {
		if($('#UserUsername').val() == "" || $('#UserPassword').val() == "" || $('#UserRepassword').val() == ""){
			alert('Debe llenar TODOS los campos de Usuario.');
			return false;
		}
		if($('#UserPassword').val() != $('#UserRepassword').val()){
			alert('El password debe ser igual en ambos campos');
			return false;
		}
    });
});
</script>
<div class="profiles form">
<?php 
	echo $form->create('Profile');
	
	echo $form->input('name',array('label'=>'Nombre'));
	
	echo $form->input('role_id',array('options'=>$roles,'label'=>'Grado'));
	
	echo $form->input('group_id',array('options'=>$groups,'label'=>'Grupo'));
	
	echo $form->input('phone_number',array('label'=>'Telefono'));
	
	echo $form->input('User.email');
	
	echo $form->input('User.username',array('label'=>'Usuario'));
	
	echo $form->input('User.password');
	
	echo $form->input('User.repassword',array('type'=>'password','label'=>'Conf. Password'));
	
	echo $form->input('User.secret_question',array('label'=>'Pregunta Secreta'));
	
	echo $form->input('User.secret_answer',array('label'=>'Respuesta Secreta'));
	
	echo $form->end('Submit');
?>
</div>