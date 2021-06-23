{include=tpl/html/header}
<div>{include=tpl/html/letras}</div>
<h1 class="tx-center">Página de contacto</h1>
<form id="contactform" method="post" action="?contactar" class="container mg-sec cont-700 cont-white">
	<h5 class="tx-center">Mandanos un mensaje y te responderemos en breve</h5>
	<div class="form-field">
		<label for="nombre">Nombre</label>
		<input type="text" name="nombre" id="nombre" class="form-in" />
	</div>
	<div class="form-field">
		<label for="email">E-mail</label>
		<input type="text" name="email" id="email" class="form-in" />
	</div>
	<div class="form-field">
		<label for="asunto">Asunto</label>
		<input type="text" name="asunto" id="asunto" class="form-in" />
	</div>
	<div class="form-field">
		<label for="mensaje">Mensaje</label>
		<textarea name="mensaje" id="mensaje" class="form-in" rows="10"></textarea>
	</div>
	<div class="form-field tx-right">
		<button type="submit" class="btn btn-primary size-l">Enviar</button>
	</div>
</form>
{include=tpl/html/footer}
