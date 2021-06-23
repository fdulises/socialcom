{include=tpl/html/header}
	<article>
		{include=tpl/html/letras}
		<div id="cont">
			<div class="container pd-z hide-s">
				<div class="gd-60 gd-b-100 pd-z">
					<div class="slideshow">
						<div class="jcslideshowcont">
							<ul class="jcslideshow" id="JCSlideshow">
								{lista_slide}
								<li>
									<a href="{articulo_enlace}" title="{articulo_titulo}">
										<img data-original="miniatura?w=690&h=400&src={articulo_portada}" />
									</a>
								</li>
								{/lista_slide}
							</ul>
							<button id="JCSbtna" class="btnslidenav atras">&laquo;</button>
							<button id="JCSbtnb" class="btnslidenav adelante">&raquo;</button>
						</div>
						<script>document.addEventListener("DOMContentLoaded", function(){activarSlideshow();})</script>
					</div>
					<div class="gd-50 pd-z">
						<div class="hide-b card-t2 cart-st1 pd-z mg-r-10">
							<div class="card-mark">
								<h3><span class="icon-thumb_up"></span> Mejor votados</h3>
								<ul>
									{lista_likes}
									<li>
										<a class="container" href="{articulo_enlace}">
											<div class="gd-80">
												<img data-original="{SITIO_URL}/miniatura?w=35&h=25&src={articulo_portada}"> {articulo_titulo}
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
					<div class="gd-50 pd-z">
						<div class="hide-b card-t2 cart-st1 pd-z">
							<div class="card-mark">
								<h3><span class="icon-eye"></span> Más Vistos</h3>
								<ul>
									{lista_hits}
									<li>
										<a class="container" href="{articulo_enlace}">
											<div class="gd-80">
												<img data-original="{SITIO_URL}/miniatura?w=35&h=25&src={articulo_portada}"> {articulo_titulo}
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
				</div>
				<div class="gd-40 hide-b card-t2">
					<div class="card-mark">
						<h3><span class="icon-star-empty"></span> En emisión</h3>
						<ul>
							{lista_emision}
							<li><a href="{articulo_enlace}"><img data-original="{SITIO_URL}/miniatura?w=40&h=30&src={articulo_portada}">  {articulo_titulo}</a></li>
							{/lista_emision}
						</ul>
					</div>
				</div>
			</div>
			<div class="container pd-z mg-sec">
				<div class="gd-70 pd-z gd-b-100">
					<div class="gd-100 flexcont">
					{lista_ultimos2}
					<div class="card-t1 gd-25 gd-m-50 gd-s-100">
						<a href="{articulo_enlace}">
							<img data-original="{SITIO_URL}/miniatura?w=600&h=600&src={articulo_portada}" />
							<h3>{articulo_titulo}</h3>
						</a>
					</div>
					{/lista_ultimos2}
					</div>
					<div class="gd-100 flexcont">
					{lista_webscrap}
					<div class="card-t1 gd-25 gd-m-50 gd-s-100">
						<a href="{articulo_enlace}">
							<img data-original="{SITIO_URL}/miniatura?w=600&h=600&src={articulo_portada}" />
							<h3>{articulo_titulo}</h3>
						</a>
					</div>
					{/lista_webscrap}
					{has_webscrap_pag}
						<div class="gd-100 mg-sec">
						<a href="{SITIO_URL}/busqueda?tipo=3}" class="btn">Ver más</a>
						</div>
					{/has_webscrap_pag}
					</div>
				</div>
				<div class="gd-30 hide-b card-t2">
					<div class="card-mark">
						<h3><span class="icon-clock"></span> Últimos</h3>
						<ul>
							{lista_ultimos}
							<li><a href="{articulo_enlace}">{articulo_titulo}</a></li>
							{/lista_ultimos}
						</ul>
					</div>
				</div>
			</div>
		</div>
	</article>
{include=tpl/html/footer}
