{include=tpl/html/perfil_header}
<div class="gd-80 gd-b-60 gd-s-100">
	<div id="perfil_premios">
		<h4>Premios</h4>
		<div class="container mg-sec flex-cont">
			{lista_items}
			<div class="gd-33 gd-m-50 gd-s-100 mg-sec">
				<img src="{cover}">
				<div class="descrip tx-center">
					<h5>{nombre}</h5>
				</div>
			</div>
			{/lista_items}
		</div>
	</div>
</div>
{include=tpl/html/perfil_footer}
