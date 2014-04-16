<style>
  #UserLoginForm{
     text-align: right;
      width: 250px;
  }
  #UserLoginForm input[type=text],
  #UserLoginForm input[type=password] {
      width: 100px;
      margin-top: 10px;
  }
</style>
<div>
    <?php
	if(empty($loginData)){
        echo "<h2>Iniciar Sesion</h2>";
        echo $form->create('User',array('action'=>"login"));
        echo $form->input('username',array('label'=>'Usuario'));
        echo $form->input('password',array('label'=>'Password'));
        echo $form->end('Ingresar');
	}else{
		echo "<h2>".$loginData['name']." conectado.</h2>";
        //pr($authUser);
	}
	?>
</div>