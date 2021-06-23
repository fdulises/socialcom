{include=tpl/html/header}
<div>{include=tpl/html/letras}</div>
<h1 class="tx-center">Usuarios referidos</h1>
<h5 class="tx-center">Invita a otros usuarios a unirse a sitio y gana puntos</h5>
<div class="container cont-700 mg-sec">
	<div class="gd-50"><b>Mi enlace para invitar usuarios:</b></div>
	<div class="gd-50" id="copiar_enlace">{SITIO_URL}/?refuser={S_USERID}</div>
</div>
<div class="container cont-700 mg-sec">
	<div class="gd-100 tx-right">
		<button class="btn btn-default" id="btn_copy">Copiar Enlace</button>
	</div>
</div>

<div class="mg-sec container cont-700">
	<h3 class="tx-center">Lista de usuarios referidos</h3>
	<div class="container">
		<div class="gd-33"><h4>Usuario</h4></div>
		<div class="gd-33"><h4>Fecha de registro</h4></div>
		<div class="gd-33"><h4>Puntos recibidos</h4></div>
	</div>
	{lista_referidos}
	<p class="container">
		<div class="gd-33"><a href="{SITIO_URL}/@{nickname}">@{nickname}</a></div>
		<div class="gd-33">{fecha}</div>
		<div class="gd-33">{puntos}</div>
	</p>
	<br>
	{/lista_referidos}
</div>
{include=tpl/html/footer}