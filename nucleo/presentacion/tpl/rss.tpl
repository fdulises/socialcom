<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>{SITIO_TITULO}</title>
		<link>{SITIO_URL}</link>
		<description>{SITIO_DESCRIP}</description>
		<language>es-MX</language>
		<generator>Mictlan CMS</generator>
		<webMaster>{SITIO_EMAIL}</webMaster>
		{lista_entradas}
		<item>
			<title>{entrada_titulo}</title>
			<link>{entrada_enlace}</link>
			<description>{entrada_descrip}</description>
			<pubDate>{TIEMPOFORMATO|r|{entrada_fecha}}</pubDate>
			<updated>{TIEMPOFORMATO|r|{entrada_fecha_u}}</updated>
			<category>{entrada_coleccion_nombre}</category>
		</item>
		{/lista_entradas}
	</channel>
</rss>
