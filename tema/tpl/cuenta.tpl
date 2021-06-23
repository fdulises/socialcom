{include=tpl/html/header}
	<article>
		<form class="container pd-z mg-sec" id="cuenta_editor" method="post" action=""  enctype="multipart/form-data">
			<div class="gd-100"><h1>Editar cuenta</h1></div>
			<div class="gd-70 gd-b-100">
				<div class="cont-white">
					<div class="form-sec">
						<label for="usuario_nombre">Nombre</label>
						<input type="text" id="usuario_nombre" name="usuario_nombre" placeholder="" class="form-in" value="{usuario_nombre}" />
					</div>
					<div class="form-sec">
						<label for="usuario_email">E-mail</label>
						<input type="text" id="usuario_email" name="usuario_email" placeholder="" class="form-in" value="{usuario_email}" />
					</div>
					<div class="form-sec">
						<label for="usuario_descrip">Descripción</label>
						<textarea id="usuario_descrip" name="usuario_descrip" class="form-in">{usuario_descrip}</textarea>
					</div>
					<div class="form-sec">
						<label for="usuario_sexo">Sexo</label>
						<select name="usuario_sexo" id="usuario_sexo" class="form-in" data-selected="{usuario_sexo}">
							<option value="0">Sin definir</option>
							<option value="1">Hombre</option>
							<option value="2">Mujer</option>
						</select>
					</div>
					<div class="form-sec pd-z">
						<div class="gd-100">
							<label>Fecha de nacimiento</label>
						</div>
						<div class="gd-30">
							<select name="fd" id="fd" class="form-in" data-selected="{usuario_fd}">
								<option value="0">Dia</option>
								{lista_fd}
								<option value="{valor}">{nombre}</option>
								{/lista_fd}
							</select>
						</div>
						<div class="gd-30">
							<select name="fm" id="fm" class="form-in" data-selected="{usuario_fm}">
								<option value="0">Mes</option>
								{lista_fm}
								<option value="{valor}">{nombre}</option>
								{/lista_fm}
							</select>
						</div>
						<div class="gd-40">
							<select name="fa" id="fa" class="form-in" data-selected="{usuario_fa}">
								<option value="0">Año</option>
								{lista_fa}
								<option value="{valor}">{nombre}</option>
								{/lista_fa}
							</select>
						</div>
					</div>
					<div class="form-sec">
						<label for="usuario_facebook">Facebook</label>
						<input type="text" id="usuario_facebook" name="usuario_facebook" placeholder="" class="form-in" value="{usuario_facebook}" />
					</div>
					<div class="form-sec">
						<label for="usuario_twitter">Twitter</label>
						<input type="text" id="usuario_twitter" name="usuario_twitter" placeholder="" class="form-in" value="{usuario_twitter}" />
					</div>
					<div class="form-sec">
						<label for="usuario_whatsapp">WhatsApp</label>
						<input type="text" id="usuario_whatsapp" name="usuario_whatsapp" placeholder="" class="form-in" value="{usuario_whatsapp}" />
					</div>
				</div>
				
				<div class="cont-white mg-sec">
					<h4>Cambiar contraseña</h4>
					<div class="form-sec">
						<label for="usuario_clave">Contraseña nueva</label>
						<input type="password" id="usuario_clave" name="usuario_clave" placeholder="" class="form-in" />
					</div>
					<div class="form-sec">
						<label for="usuario_reclave">Repetir contraseña</label>
						<input type="password" id="usuario_reclave" name="usuario_reclave" placeholder="" class="form-in" />
					</div>
					<button id="usuario_btnclave" class="btn btn-default" type="button">Guardar contraseña</button>
				</div>
			</div>
			<div class="gd-30 gd-b-100">
				<div class="form-sec cont-white" id="img_avatar_cont">
					<p><label for="avatar">Avatar</label></p>
					<div class="form-upload" id="form-avatar-box" data-imgsource="{usuario_avatar}"></div>
					<input type="file" id="avatar" name="avatar" class="hide" />
					<button type="button" class="btn btn-block" id="form-avatar-btn" data-upload-source="#avatar">Seleccionar imagen</button>
				</div>
				<div class="form-sec cont-white" id="img_cover_cont">
					<p><label for="cover">Imagen de Portada</label></p>
					<div class="form-upload" id="form-cover-box" data-imgsource="{usuario_cover}"></div>
					<input type="file" id="cover" name="cover" class="hide" />
					<button type="button" class="btn btn-block" id="form-cover-btn" data-upload-source="#cover">Seleccionar imagen</button>
				</div>
				<div class="form-sec cont-white">
					<button type="submit" id="enviar" class="btn btn-primary size-l btn-block">Guardar Cambios</button>
				</div>
			</div>
		</form>
	</article>
	<script src="{TEMA_URL}/js/sha512.js"></script>
{include=tpl/html/footer}
