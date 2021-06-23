{include=tpl/html/header}
	<article>
		<div id="cont">
			<form class="container pd-z mg-sec" id="post_editor" method="post" action="{entrada_action}">
				<div class="gd-100"><h1>Editor de post</h1></div>
				<div class="gd-70 gd-b-100">
					<div class="cont-white">
						<div class="form-sec">
							<label for="titulo">Titulo</label>
							<input type="text" id="titulo" name="titulo" placeholder="" class="form-in" value="{entrada_titulo}" />
						</div>
						<div class="form-sec">
							<label for="url">Slug</label>
							<input type="text" id="url" name="url" placeholder="" class="form-in" value="{entrada_url}" />
						</div>
						<div class="form-sec">
							<label for="contenido">Contenido</label>
							<textarea rows="15" id="contenido" name="contenido" class="form-in ckeditor">{entrada_contenido}</textarea>
						</div>
						<div class="form-sec">
							<label for="descargas">Enlaces de descarga (uno por linea)</label>
							<textarea id="descargas" name="descargas" class="form-in">{entrada_descargas}</textarea>
						</div>
						<div class="form-sec">
							<label for="descrip">Descripción</label>
							<textarea id="descrip" name="descrip" class="form-in">{entrada_descrip}</textarea>
						</div>
					</div>
				</div>
				<div class="gd-30 gd-b-100">
					<div class="form-sec cont-white">
						<label for="categoria">Categoría</label>
						<select name="categoria" id="categoria" class="form-in">
							<option value="0">Sin categoría</option>
							{lista_categorias}
							<option value="{id}" {selected}>{nombre}</option>
							{/lista_categorias}
						</select>
					</div>
					<div class="form-sec cont-white">
						<h4>Configuraciones</h4>
						<div class="toggle-group">
							<input name="puntos_s" type="checkbox" id="puntos_s" class="dataToogle" data-destino="#puntos_box">
							<label for="puntos_s">
								<span class="aural">Show:</span> Solicitar puntos por ver
							</label>
							<div class="onoffswitch pull-right" aria-hidden="true">
								<div class="onoffswitch-label">
									<div class="onoffswitch-inner"></div>
									<div class="onoffswitch-switch"></div>
								</div>
							</div>
						</div>
						<div class="toggle-group">
							<input name="imgurl_s" type="checkbox" id="imgurl_s">
							<label for="imgurl_s">
								<span class="aural">Show:</span> Agregar portada por URL
							</label>
							<div class="onoffswitch pull-right" aria-hidden="true">
								<div class="onoffswitch-label">
									<div class="onoffswitch-inner"></div>
									<div class="onoffswitch-switch"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-sec cont-white" id="cover_cont">
						<p><label for="cover">Imagen de Portada</label></p>
						<div class="form-upload" id="form-img-box"></div>
						<input type="file" id="cover" name="cover" placeholder="" class="form-in" />
						<button type="button" class="btn btn-block" id="form-img-btn">Seleccionar imagen</button>
					</div>
					<div class="form-sec cont-white" id="cover_cont_url">
						<label for="cover_url">Imagen de Portada</label>
						<div class="form-upload" id="form-imgurl-box"></div>
						<input type="text" id="cover_url" name="cover_url" placeholder="Ingrese una URL valida" class="form-in" value="{entrada_cover}" />
					</div>
					<div class="form-sec cont-white" id="puntos_box">
						<label for="puntosv">Puntos por ver el post</label>
						<select name="puntosv" id="puntosv" class="form-in">
							<option value="0">0 puntos (Todos lo pueden ver)</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
						</select>
					</div>
					<div class="form-sec cont-white">
						<p><label for="enviar">Publicación</label></p>
						<button type="submit" id="enviar" class="btn btn-primary size-l btn-block">Guardar Entrada</button>
					</div>
				</div>
			</form>
		</div>
	</article>
	<script src="{SITIO_URL}/media/ckeditor/ckeditor.js"></script>
{include=tpl/html/footer}
