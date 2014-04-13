<div>
	<ul>
	<?php
		//pr($leagues);
		$act = 'add';
		if($horse)
			$act = 'add_horse';
		foreach ($leagues as $lk => $lv){
			echo "<li style='margin-top:3px'>";
			echo $html->link($lv,array('controller'=>'games','action'=>$act,$date,$lk));
			echo "</li>";
		}
	?>
	</ul>
</div>