{include=tpl/html/header}
<div>{include=tpl/html/letras}</div>
<h1 class="tx-center">Lo mejor del sitio</h1>

<div class="container mg-sec">
	<h2 class="tx-center">Listas top entradas</h2>
	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-eye"></span> Más visitas</h3>
				<ul>
					{lista_hits}
					<li>
						<a class="container" href="{articulo_enlace}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=25&h=25&src={articulo_portada}"> {articulo_titulo}
							</div>
							<div class="gd-20">
								<span class="icon-eye"></span> {articulo_hits}
							</div>
						</a>
					</li>
					{/lista_hits}
				</ul>
			</div>
		</div>
	</div>
	
	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-coin-dollar"></span> Más puntos</h3>
				<ul>
					{lista_puntos}
					<li>
						<a class="container" href="{articulo_enlace}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=25&h=25&src={articulo_portada}"> {articulo_titulo}
							</div>
							<div class="gd-20">
								<span class="icon-coin-dollar"></span> {articulo_puntos}
							</div>
						</a>
					</li>
					{/lista_puntos}
				</ul>
			</div>
		</div>
	</div>
	
	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-thumb_up"></span> Más likes</h3>
				<ul>
					{lista_likes}
					<li>
						<a class="container" href="{articulo_enlace}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=25&h=25&src={articulo_portada}"> {articulo_titulo}
							</div>
							<div class="gd-20">
								<span class="icon-thumb_up"></span> {articulo_likes}
							</div>
						</a>
					</li>
					{/lista_likes}
				</ul>
			</div>
		</div>
	</div>
	
</div>


<div class="container mg-sec">
	<h2 class="tx-center">Listas top usuarios</h2>
	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-file-text"></span> Más entradas</h3>
				<ul>
					{lista_ue}
					<li>
						<a class="container" href="{SITIO_URL}/@{nickname}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=20&h=20&src={avatar}"> @{nickname}
							</div>
							<div class="gd-20">
								<span class="icon-file-text"></span> {total_e}
							</div>
						</a>
					</li>
					{/lista_ue}
				</ul>
			</div>
		</div>
	</div>

	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-coin-dollar"></span> Más puntos</h3>
				<ul>
					{lista_ux}
					<li>
						<a class="container" href="{SITIO_URL}/@{nickname}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=20&h=20&src={avatar}"> @{nickname}
							</div>
							<div class="gd-20">
								<span class="icon-coin-dollar"></span> {experiencia}
							</div>
						</a>
					</li>
					{/lista_ux}
				</ul>
			</div>
		</div>
	</div>

	<div class="gd-33 gd-b-50 gd-s-100 pd-z">
		<div class="card-t2 cart-st1 pd-z mg-r-10">
			<div class="card-mark">
				<h3><span class="icon-user-check"></span> Más seguidos</h3>
				<ul>
					{lista_us}
					<li>
						<a class="container" href="{SITIO_URL}/@{nickname}">
							<div class="gd-80">
								<img src="{SITIO_URL}/miniatura?w=20&h=20&src={avatar}"> @{nickname}
							</div>
							<div class="gd-20">
								<span class="icon-user-check"></span> {seguidores}
							</div>
						</a>
					</li>
					{/lista_us}
				</ul>
			</div>
		</div>
	</div>
					
</div>

{include=tpl/html/footer}