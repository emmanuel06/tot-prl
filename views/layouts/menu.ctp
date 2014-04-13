<ul id="jsddm">
	<?php 	
	if(!empty($authUser)){
		?>	
		<li>
			<?php echo $html->link("Inicio",array('controller'=>'pages','action'=>'display','home','admin'=>0))?>
		</li>
		<?php
		if($authUser['role_id'] == ROLE_ADMIN){	
		?>
			<li>
				<?php echo $html->link("Usuarios",array('#'))?>
				<ul>
					<li>
						<?php echo $html->link("Administrar",array('controller'=>'profiles','action'=>'index','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Grupos",array("controller"=>"groups",'action'=>'index','admin'=>1))?>
					</li>
				</ul>
			</li>
			<li>
				<?php echo $html->link("Tablas",array('#'))?>
				<ul>
					<li>
						<?php echo $html->link("Deportes",array('controller'=>'sports','action'=>'index','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Ligas",array('controller'=>'leagues','action'=>'index','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Equipos",array('controller'=>'teams','action'=>'index','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Pitchers",array('controller'=>'pitchers','action'=>'index','admin'=>1))?>
					</li>
				</ul>
			</li>
			<li>
				<?php echo $html->link("Juegos",array('#'))?>
				<ul>
					<li>
						<?php echo $html->link("Admin./Logros",array('controller'=>'games','action'=>'index','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Colocar Pitchers",array('controller'=>'games','action'=>'set_pitchers','admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Hoja de Logros",array('controller'=>'games',"action"=>"hoja",'admin'=>1))?>
					</li>
					<li>
						<?php echo $html->link("Resultados",array('controller'=>'results',"action"=>"index",'admin'=>1))?>
					</li>
				</ul>
			</li>
			<li>
				<?php echo $html->link("Ventas",array('#'))?>
				<ul>
					<li>
						<?php echo $html->link("Tickets",array('controller'=>'tickets','action'=>'tickets','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Totales",array('controller'=>'tickets','action'=>'sales','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Pagar",array('controller'=>'tickets','action'=>'pay','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Reportados",array('controller'=>'tickets','action'=>'anull','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Anular",array('controller'=>'tickets','action'=>'anull_ticket','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Vencidos",array('controller'=>'tickets','action'=>'outdated','admin'=>1)) ?>
					</li>
				</ul>
			</li>
			<li>
				<?php echo $html->link("Avanzado",array('#')) ?>
				<ul>
					<li>
						<?php echo $html->link("Seguimiento",array('controller'=>'odds','action'=>'follow','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Auditoria",array('controller'=>'operations','action'=>'index','admin'=>1)) ?>
					</li>
				</ul>
			</li>
			
		<?php 
		
		}elseif($authUser['role_id'] == ROLE_TAQUILLA){
		
		?>
		
			<li>
				<?php echo $html->link("Mi perfil",array('controller'=>'profiles','action'=>'my_view','admin'=>1)) ?>
			</li>
			<li>
				<?php echo $html->link("Hoja de logros",array('controller'=>'games','action'=>'hoja','admin'=>1)) ?>
			</li>
			<li>
				<?php echo $html->link("Ventas",array('#'))?>
				<ul>
					<li>
						<?php echo $html->link("Vender",array('controller'=>'tickets','action'=>'add','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Mis Tickets",array('controller'=>'tickets','action'=>'tickets_taquilla','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Mis Ventas",array('controller'=>'tickets','action'=>'sales_taquilla','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Pagar",array('controller'=>'tickets','action'=>'pay','admin'=>1)) ?>
					</li>
					<li>
						<?php echo $html->link("Reportar",array('controller'=>'tickets','action'=>'report','admin'=>1)) ?>
					</li>
				</ul>
			</li>
	<?php
	 
		}
		
	}
	
	?>	
</ul>