<div class="sports form">
<?php echo $form->create('Sport');?>
	<table border='1'>
		<tr>
			<td>
				Nombre
			</td>
			<td>
			<?php
				echo $form->input('id');
				echo $form->input('name',array('label'=>''));
			?>
			</td>
		</tr>
		<tr>
			<td>
				Empata
			</td>
			<td>
			<?php	
				echo $form->input('get_draw',array('label'=>''));
			?>
			</td>
		</tr>
		<tr>
			<td>
				Apuesta Principal
			</td>
			<td>
			<?php 
				echo $form->radio('default_type',array(1=>'Moneyline',2=>'Runline'),array('legend'=>false));
			?>
			</td>
		</tr>
	</table>
<?php echo $form->end('Guardar');?>
</div>