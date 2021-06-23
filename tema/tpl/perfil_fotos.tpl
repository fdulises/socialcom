{include=tpl/html/perfil_header}
<div class="gd-80 gd-b-60 gd-s-100">
	<div id="perfil_fotos">
		<h4>Fotos</h4>
		{is_owner}
		<form id="form-fotoupload" method="post" action="?subida" class="mg-sec" enctype="multipart/form-data">
			<label for="foto">
				<input type="file" id="foto" name="foto" class="oculto" />
				<button class="btn btn-primary" type="button" id="perfilFotoSubidaBtn"><span class="icon-plus"></span> Agregar Foto</button>
			</label>
			<button class="btn btn-default" type="submit"><span class="icon-upload"></span> Subir</button>
			<div id="prevFoto"></div>
		</form>
		{/is_owner}
		<div class="container flexcont imglbg" id="fotos-cont">
			{lista_fotos}
			<div class="gd-33 gd-m-50 gd-s-100 fotos-element mg-sec" data-id="{id}">
				{is_owner}<div class="btndelete-cont">
					<button class="btn btn-primary size-s" data-id="{id}"><span class="icon-cross"></span></button>
				</div>{/is_owner}
				<a href="{url}"><img src="{url}"></a>
			</div>
			{/lista_fotos}
		</div>
	</div>
</div>
{include=tpl/html/perfil_footer}
