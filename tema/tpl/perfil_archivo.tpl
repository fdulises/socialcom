{include=tpl/html/perfil_header}
<div class="gd-80 gd-b-60 gd-s-100">
	<div class="gd-60 gd-b-100" id="perfil_publi">
		<h4>Entradas</h4>
		{no_articulos}
		<div class="mg-sec">
			<div class="alert">
				<span class="icon-info"></span> &nbsp; Aún no hay entradas que mostrar
			</div>
		</div>
		{/no_articulos}
		{lista_articulos}
		<a href="{articulo_enlace}">
			<article class="container perfil-archivo">
				<div class="gd-20">
					<img src="{SITIO_URL}/miniatura?w=200&h=180&src={articulo_portada}">
				</div>
				<div class="gd-80">
					<h5>{articulo_titulo}</h5>
				</div>
			</article>
		</a>
		{/lista_articulos}
		{si_paginacion}
		<div class="mg-sec tx-center">
			{is_paginacion_a}
			<a href="{paginacion_enlace_a}" class="btn">&laquo; Anterior</a>
			{/is_paginacion_a}
			{is_paginacion_s}
			<a href="{paginacion_enlace_s}" class="btn">siguiente &raquo;</a>
			{/is_paginacion_s}
		</div>
		{/si_paginacion}
	</div>
	<div class="gd-40 hide-b card-t2" id="pefil_post">
		<div class="card-mark">
			<h3><span class="icon-clock"></span> Ultimos temas agregados</h3>
			<ul>
				{lista_ultimos}
				<li><a href="{articulo_enlace}"><img src="{SITIO_URL}/miniatura?w=40&h=30&src={articulo_portada}"> {articulo_titulo}</a></li>
				{/lista_ultimos}
			</ul>
		</div>
	</div>
</div>
{include=tpl/html/perfil_footer}
