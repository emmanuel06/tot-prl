<?php
class User extends AppModel {

	var $name      = 'User';

    var $belongsTo = array('Group','Role');

    // VALIDATIONS :: http://lemoncake.wordpress.com/2007/07/03/all-about-validation-in-cakephp-12/

    // VALUES :: http://cakebaker.42dh.com/2007/01/03/validation-with-cakephp-12/
    var $validate = array(
        'name'      =>  array(
                'required' => array(
                        'rule'    => VALID_NOT_EMPTY,
                        'message' => 'El nombre es obligatorio.'
                )
        )
        ,
        'username' => array(
                'required' => array(
                        'rule'    => 'notEmpty',
                        'message' => 'El usuario es obligatorio.'
                ),
                'isUnique' => array(
                        'rule'    => 'isUnique',
                        'message' => 'El usuario ya existe.'
                ),
                'between' => array(
                        'rule'    => array('between', 5, 15),
                        'message' => 'Debe ser entre 5 y 15 caracteres.'
                )
        )
        ,
        'password' => array(
                'required' => array(
                        'rule'    => 'notEmpty',
                        'message' => 'El usuario es obligatorio'
                ),
                'longPass' => array(
                        'rule'    => array('comparison', '>=', 6),
                        'message' => 'Debe ser mayor a 6 caracteres.'

                )
        ),
        'conf_pass' => array(
                'equalToField' => array(
                        'rule'    => array('equaltofield','password'),
                        'message' => 'El password y la conf. deben tener el mismo valor.',
                )
        )
    );

    function equalToField($check,$otherfield)
    {
        //get name of field
        $fname = '';
        foreach ($check as $key => $value){
            $fname = $key;
            break;
        }

        return $this->data[$this->name][$otherfield] === AuthedComponent::password($this->data[$this->name][$fname]);

    }

}
?>