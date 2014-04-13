<?php
class Operation extends AppModel {

	var $name = 'Operation';
	
	var $belongsTo = array(
		'OperationType' => array('fields'=>'name'),
		'Profile' => array('fields'=>'name')
	);
	
	function ins_op($type,$profile,$model,$id="",$met=""){		
		$mydata = array('operation_type_id'=>$type,'profile_id'=>$profile,'metainf'=>$model,
			'model_id'=>$id,'metadata'=>$met
		);
		$this->save($mydata);
	}
	/* OPERACIONES SEGUIDAS:
	 * 
	 * Login
	 * Logout
	 * 
	 * ADMIN:
	 * 
	 * Creacion de Juego
	 * Edicion de Juego
	 * Hab/Susp un juego
	 * Liberar Liga
	 * Set Pitchers
	 * Agregar/Modificar logros en todos sus modos (deberia hacer el historial de logros)
	 * Resultados
	 * Pagar
	 * Anular como centro
	 * Reportados pasarlos a Anulados
	 * Pasar a Vencidos de una fecha
	 * 
	 * TAQUILLA:
	 * 
	 * pagar
	 * anular
	 * 
	 */
}
?>
