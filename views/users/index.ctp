<style>
.users_index{
    border: 1px solid forestgreen;
}
</style>
<div class="users_index">

<ul>
    <li><?php echo $html->link('Agregar',array('action'=>'add')) ?></li>
</ul>
<?php
pr($users);
?>
</div>
<script>
    /**
     *  -> HEY :: I need a plugin jquery to create buttons
     *  - These buttons has URL to load and everything
     *  - All is ajax, no more redir. (even enab-disab)
     *  - A modal creator to set the most common values on the modal to show it
     */
</script>