function errorShow(errorlist){
	//nickname_vacio","email_vacio","usuario_incorrecto","email_incorrecto
	var errortext = '';
	if( -1 != errorlist.indexOf("nickname_vacio") || -1 != errorlist.indexOf("usuario_incorrecto") )
		errortext += '<li>El nombre de usuario ingresado es incorrecto <br>(El nombre de usuario solo puede contener letras numero y guiones)</li>';
	if( -1 != errorlist.indexOf("usuario_repetido") )
		errortext += '<li>El nombre de usuario ya existe</li>';
	if( -1 != errorlist.indexOf("email_vacio") || -1 != errorlist.indexOf("email_incorrecto")  )
		errortext += '<li>El E-mail ingresado es incorrecto</li>';
	if( -1 != errorlist.indexOf("email_repetido") )
		errortext += '<li>El E-mail ya esta registrado</li>';
	if( -1 != errorlist.indexOf("clave_vacio") )
		errortext += '<li>Campo contraseña esta vacio</li>';
	if( -1 != errorlist.indexOf("clave_incorrecto") )
		errortext += '<li>La contraseña enviada es incorrecta</li>';
	if( -1 != errorlist.indexOf("ip_registrada") )
		errortext += '<li>No se pueden registrar tantos usuarios con la misma dirección IP</li>';
	listefi.alert('<ul>'+errortext+'</ul>', 'Tienes algunos errores:');
}
document.addEventListener('DOMContentLoaded', function(){
	document.querySelector("#login-form").addEventListener('submit', function(e){
		e.preventDefault();
		listefi.ajax({
			method: 'post',
			url: this.action,
			data: {
				clave: hex_sha512(this.clave.value),
				nickname: this.nickname.value,
				email: this.email.value
			},
			success: function(resultado){
				resultado = JSON.parse(resultado);
				if(resultado.estado == 1){
					document.location.href = SITIO_URL+'/cuenta';
				}else errorShow(resultado.error);
			}
		});
	});
});
