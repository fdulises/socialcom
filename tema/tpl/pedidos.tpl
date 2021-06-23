{include=tpl/html/header}
<div>{include=tpl/html/letras}</div>
<h1 class="tx-center">Página de pedidos</h1>
<form id="pedidosform" method="post" action="" class="container mg-sec cont-700 cont-white">
	<h5 class="tx-center">Solicitar pedido</h5>
	<div class="form-field">
		<label for="nombre">Nombre</label>
		<input type="text" name="nombre" id="nombre" class="form-in" />
	</div>
	<div class="form-field tx-right">
		<button type="submit" class="btn btn-primary size-l">Enviar</button>
	</div>
</form>

<div class="mg-sec container cont-700">
	<div class="container">
		<div class="gd-80"><h4>Ultimos pedidos</h4></div>
		<div class="gd-20"><h4>Cumplido</h4></div>
	</div>
	{lista_pedidos}
	<p class="container">
		<div class="gd-80">{nombre}</div>
		<div class="gd-20">{estado}</div>
	</p>
	<br>
	{/lista_pedidos}
</div>
{include=tpl/html/footer}
