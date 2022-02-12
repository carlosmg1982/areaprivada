<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <h2>Bienvenido/a <?=$usuario['nombre'].' '.$usuario['apellidos']?></h2>
            <div class="row">
                <div class="large-12 columns">
					<? if($usuario['finalizado']) { ?>
						<? if(!$usuario['corregido'] || !$usuario['verCalificacion']) { ?>
						<p>
							Al haber notificado la finalizacion de la actividad, la plataforma Omeka ya no es accesible, para no influir en el proceso de corrección del ejercicio.
						</p>
						<? } else { ?>
						<p>
							Puedes acceder a tu plataforma Omeka personalizada a través de la siguiente dirección:
							<a href="https://cursodigitalizacionfuned.com/FINALIZADO<?=$usuario['dni']?>/" target="_blank">https://cursodigitalizacionfuned.com//FINALIZADO<?=$usuario['dni']?>/</a>.
						</p>
						<? } ?>
					<? } else { ?>
					<p>
						Debes acceder a la siguiente dirección para iniciar la práctica:
						<a href="https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/" target="_blank">https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/</a>.
					</p>
					<? } ?>
					<? if(!$usuario['finalizado']) { ?>
					<p>Una vez finalizado el proceso de instalación de la herramienta Omeka, dispondremos de dos accesos diferenciados:</p>
					<ul>
						<li>Front-end o área pública para los visitantes del sitio Web: <a href="https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/" target="_blank">https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/</a>.</li>
						<li>Back-end o área privada (el Panel de Control) desde donde se gestiona la plataforma y sus contenidos, y para la que utilizaremos el usuario y contraseña especificados durante el proceso de instalación: <a href="https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/admin/" target="_blank">https://cursodigitalizacionfuned.com/<?=strtoupper($usuario['grupo']).$usuario['dni']?>/admin/</a>.</li>
					</ul>
					<? } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
		<div class="panel">
			<? if($usuario['finalizado']) { ?>
				<? if(!$usuario['corregido'] || !$usuario['verCalificacion']) { ?>
				<h2>Finalización de actividad</h2>
				<div class="row">
					<div class="large-12 columns">
						<p>
                            <?php
                            $date = new DateTime($configuracion['fechaLimite']);
                            $date->add(new DateInterval('P1D'));
                            $fechaNotas = $date->format('Y-m-d');
                            ?>
							La fecha de notificación de actividad completada fue el <?=strftime('<strong>%d/%m/%Y</strong> a las <strong>%H:%M</strong>',strtotime($usuario['fechaNotificacion']))?>. Las calificaciones se publicarán en la plataforma cuando se cumpla el plazo de entrega de la actividad, a partir del <strong><?=strftime('%d/%m/%Y',strtotime($fechaNotas))?></strong>.
						</p>
					</div>
				</div>
				<? } else { ?>
                    <h1>Calificación obtenida: <span class=""nota"><? echo $usuario['nota'] ?> / 10</span></h1>
                <?
                if($usuario['retraso']) {
                    $fechaEntrega = new \DateTime($usuario['fechaNotificacion']);
                    $fechaLimite = new \DateTime($configuracion['fechaLimite']);
                    $intervalo = $fechaEntrega->diff($fechaLimite);

                    $semanas = ceil(intval($intervalo->format('%a'))/7 );
                    ?>
                    <p class="retraso">
                        Se ha aplicado una penalización de <span class="nota"><?=$semanas?></span> puntos por la entrega tardía de la práctica
                        (fecha límite: <span class="nota"><?=date_format($fechaLimite,'d/m/Y H:i')?></span>;
                        fecha entrega: <span class="nota"><?=date_format($fechaEntrega,'d/m/Y H:i')?></span>)
                    </p>
                <? } ?>
				<h2>Ampliación acerca de los errores cometidos en el ejercicio</h2>
				<div class="row">
					<div class="large-12 columns">
						<?
						if( count($usuario['notas_alumnos']) ) {
							foreach($usuario['notas_alumnos'] as $nota) {
								?>
								<h3><?=$nota['bloque']?></h3>
								<p><?=$nota['descripcion']?></p>
								<p><?=$nota['comentarios']?></p>
								<table class="calificaciones">
									<tr>
										<th>Original</th>
										<th>Alumno</th>
									</tr>
									<tr>
										<td><?=htmlspecialchars($nota['original'])?></td>
										<td><?=htmlspecialchars($nota['alumno'])?></td>
									</tr>
								</table>
							<? 
							}
						}
						?>
					</div>
				</div>
				<? } ?>
			<? } else { ?>
				<h2>Finalización de actividad</h2>
				<form action="<?php echo site_url('main/entrega_action'); ?>" method="post">
					<div class="row collapse">
						<div class="large-12 columns">
							<p>
								Para confirmar la finalización de la actividad, pulsa el botón Finalizar. Recuerda que, una vez realizado este paso, la plataforma Omeka ya no será accesible para no influir en el proceso de corrección del ejercicio.
							</p>
						</div>								
					</div>							
					<button type="submit" class="radius button">Finalizar</button>
				</form>
			<? } ?>
		</div>
    </div>
</div>
