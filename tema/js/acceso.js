function errorShow(errorpst){
	var errortext = '';
	if( -1 != errorpst.indexOf("usuario_incorrecto") || -1 != errorpst.indexOf("clave_incorrecta") )
		errortext += '<p>El nombre de usuario o la contraseña son erroneos</p>';
	if( -1 != errorpst.indexOf("usuario_inexistente") )
		errortext += '<p>El nombre de usuario ingresado no existe, favor de verificarlo</p>';
	listefi.alert(errortext, 'Error: No se pudo iniciar sesión');
}
document.addEventListener('DOMContentLoaded', function(){
	document.querySelector("#login-form").addEventListener('submit', function(e){
		e.preventDefault();
		listefi.ajax({
			method: 'post', url: this.action,
			data: {
				clave: hex_sha512(this.clave.value),
				usuario: this.usuario.value,
			},
			success: function(resultado){
				resultado = JSON.parse(resultado);
				if(resultado.estado == 1){
					document.location.href = SITIO_URL;
				}else errorShow(resultado.error);
			}
		});
	});
});
