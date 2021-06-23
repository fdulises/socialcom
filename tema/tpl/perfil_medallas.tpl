{include=tpl/html/perfil_header}
<div class="gd-80 gd-b-60 gd-s-100">
	<div class="gd-60 gd-b-100" id="perfil_medallas">
		<h4>Medallas</h4>
		<div class="container flex-cont">
			{logros_list}
			<div class="gd-25 gd-m-33 gd-s-50 gd-x-100 tx-center mg-sec">
				<img src="{cover}" title="{nombre}">
			</div>
			{/logros_list}
		</div>
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
