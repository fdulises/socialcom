{include=tpl/html/header}
	<article>
		{include=tpl/html/letras}
		<div id="cont">
			<form id="cont-fbusq" class="container pd-z mg-sec" method="get" action="{SITIO_URL}/busqueda">
				<div id="f-sbusq" class="gd-40 gd-m-100">
					<input class="form-in" name="b" type="text" placeholder="Busqueda por texto" value="{cadenabusqueda}">
					<button class="btn btn-primary" type="submit"><span class="icon-search"></span></button>
				</div>
				<div class="gd-30 gd-m-100">
					<select class="form-in" name="order" id="filtro_orden">
						<option value="">Ordern Automatico</option>
						<option value="likes">Ordernar por Likes</option>
						<option value="hits">Ordernar por Vistas</option>
						<option value="puntosv">Ordernar por Puntos</option>
					</select>
				</div>
				<div class="gd-30 gd-m-100">
					<input class="form-in size-l" name="u" type="text" placeholder="Busqueda por autor" value="{userbusqueda}">
					<span class="icon icon-user form-decoration"></span>
				</div>
			</form>
			<div class="container pd-z mg-sec flexcont">
				{lista_articulos}
				<div class="card-t1 gd-20 gd-b-25 gd-m-50 gd-s-100">
					<a href="{articulo_enlace}">
						<img src="{SITIO_URL}/miniatura?w=600&h=600&src={articulo_portada}" />
						<h3>{articulo_titulo}</h3>
					</a>
				</div>
				{/lista_articulos}
				{no_articulos}
				<div class="alert gd-100">
					<strong>¡No se encontrarón resultados!</strong>
				</div>
				{/no_articulos}
			</div>
			<div class="container mg-sec flexcont">
				{is_paginacion_a}
				<a href="{paginacion_enlace_a}" class="btn size-l">&laquo; Anterior</a>
				{/is_paginacion_a}
				{is_paginacion_s}
				<a href="{paginacion_enlace_s}" class="btn size-l">Siguiente &raquo;</a>
				{/is_paginacion_s}
			</div>
		</div>
	</article>
{include=tpl/html/footer}
