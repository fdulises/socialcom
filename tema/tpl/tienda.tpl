{include=tpl/html/header}
<div>{include=tpl/html/letras}</div>
<h1 class="tx-center">Tienda de premios</h1>
<h5 class="tx-center">Canjea tus puntos por items para tu perfil</h5>
<div class="container mg-sec flex-cont">
	{lista_productos}
	<div class="gd-33 gd-m-50 gd-s-100 mg-sec card-product">
		<img src="{cover}">
		<div class="descrip">
			<h4>{nombre}</h4>
			<h5><span class="icon-coin-dollar"></span> {precio} puntos</h5>
		</div>
		<button data-precio="{precio}" data-id="{id}" class="btn btn-default btn-block size-l btncomprar"><span class="icon-cart"></span> Comprar</button>
	</div>
	{/lista_productos}
</div>
<div id="puntos-cont" class="oculto">
	Puntos: <span class="icon-coin-dollar"></span> <span id="user-puntos">{USER_PUNTOS}</span>
</div>
{include=tpl/html/footer}
