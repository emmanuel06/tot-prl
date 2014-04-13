<?php 
echo $form->input("Team.$team_type.pitcher",array(
	'options'=>$pitchers,'label'=>'','class'=>'list_pitch','empty'=>'Seleccione'
));
?>