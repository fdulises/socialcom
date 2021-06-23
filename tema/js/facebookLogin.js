var facebookLoginBtn = document.getElementById("facebook-login-button");
var facebookAppID = facebookLoginBtn.getAttribute("appId");
var hasloginclick = false;

function statusChangeCallback(response) {
	if (response.status === 'connected') testAPI();
}

function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
} 

window.fbAsyncInit = function() {
	FB.init({
		appId      : facebookAppID,
		cookie     : true,
		xfbml      : true,
		version    : 'v2.8'
	});
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
};
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function testAPI() {
	FB.api('/me?fields=id,name,email', function(response) {
		if( hasloginclick ){
			listefi.ajax({
				url: SITIO_URL+'/registro?fbreg',
				method: 'post',
				data: {
					fb_id: response.id,
					fb_name: response.name,
					fb_email: response.email,
				},
				success: function(result){
					result = JSON.parse(result);
					if( result.estado == 1 ) document.location.href = SITIO_URL+'?lorem';
					else listefi.alert("<p>Ha ocurrido un error al intentar iniciar sesión</p><p>Por favor recarga la pagina y vuelve a intentar.</p>", "Error");
				}
			});
		}
	});
}

facebookLoginBtn.addEventListener("click", function(){
	hasloginclick = true;
	FB.login(function(response){
		checkLoginState();  
	}, {scope: 'public_profile, email'});  
});