<script>
$(function () {
   //alert('adding...');
});
</script>
<style>
.users_add{
    width: 400px;
}
</style>
<div class="users_add">
<?php
echo $form->create('User');
echo $form->input('name');
echo $form->input('username');
echo $form->input('password');
echo $form->input('conf_pass',array('type'=>'password'));
echo $form->end("Crear Usuario");
?>
</div>