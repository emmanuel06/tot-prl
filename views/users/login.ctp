<style>
  #logpart{
      width: 250px;

  }
  #UserLoginForm{
      text-align: right;
  }
  #UserLoginForm input[type=text],
  #UserLoginForm input[type=password] {
      width: 100px;
      margin-top: 10px;
  }
</style>
<div id="logpart">
    <?php
	if(empty($authUser)){
        echo "<h2>Iniciar Sesion</h2>";
        echo $form->create('User',array('action'=>"login"));
        echo $form->input('username',array('label'=>'Usuario'));
        echo $form->input('password',array('label'=>'Password'));
        echo $form->end('Ingresar');
	}else{
		echo "<h2>".$authUser['name']." en linea.</h2>";
        //pr($authUser);
	}
	?>
</div>