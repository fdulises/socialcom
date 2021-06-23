{include=tpl/html/header}
	<article>
		{include=tpl/html/letras}
		<div id="cont">
			<div class="container pd-z mg-sec">
				<h1>Archivo de la categoría {pagina_titulo}</h1>
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
			<div class="container mg-sec">
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
