<div class="container comentario_li" data-id="<?php echo $v['public_id'] ?>">
	<div class="gd-20 gd-m-100">
		<img class="coment_avatar" src="<?php echo $v['public_avatar'] ?>">
	</div>
	<div class="gd-80 gd-m-100">
		<div class="gd-60 gd-s-100">
			<div class="coment_autor">
				<a href="{SITIO_URL}/@<?php echo $v['public_autor'] ?>">@<?php echo $v['public_autor'] ?></a>
			</div>
			<div class="coment_fecha"><?php echo $v['public_fecha'] ?></div>
		</div>
		<?php if( $delbtncond ): ?>
		<div class="gd-40 gd-s-100 tx-right">
			<button class="btn btn-default size-s" data-publicdel="<?php echo $v['public_id'] ?>" onclick="publicComentDel(this)">
				<span class="icon-blocked"></span> Eliminar
			</button>
		</div>
		<?php endif; ?>
		<div class="coment_cont gd-100"><?php echo $v['public_contenido'] ?></div>
	</div>
</div>