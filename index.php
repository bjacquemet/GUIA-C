<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Guia-C</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
   	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    <script src='http://api.tiles.mapbox.com/mapbox.js/v0.6.6/mapbox.js'></script>
  <link href='http://api.tiles.mapbox.com/mapbox.js/v0.6.6/mapbox.css' rel='stylesheet' />
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" title="no title" charset="utf-8">
</head>
<body>
<?php
require 'my.php';
require 'agregar.php';
	
ini_set("display_errors", 1);
error_reporting(E_ALL ^ E_NOTICE);

$dbhandle = mysql_connect($dbhost, $dbuser, $dbpass) or die("Couldn't connect to SQL Server on");

mysql_select_db($dbname, $dbhandle);
$query = 'SELECT * FROM LUGAR, EVENTOS_COUNT WHERE LUGAR.idLugar = EVENTOS_COUNT.idLugar GROUP BY LUGAR.idLugar;';

$result = mysql_query($query) or die ('not working' .mysql_error());
?>

<div id='container'>
	<div id="nav-bar">
	<div id="header">
		<div id='logo'>
			<img src='img/guiaC.png' height='50px' alt='logo'/>
		</div>
		<ul>
			<li><a href='http://data.buenosaires.gob.ar/dataset' target="_blank" id='linkdatos'>Datos</a></li><span style='font-size:15pt'>|</span>
			<li><a href='https://github.com/bjacquemet/GUIA-C' target="_blank" id='linkfuentes'>Fuentes</a></li>
		</ul>
	</div>
<script type="text/javascript" charset="utf-8">
$('#linkacerca').click(function(){
	$('#acerca').css('display', 'block');
});
$('#linkdatos').click(function(){
	$('#datos').css('display', 'block');
});
$('#linkfuentes').click(function(){
	$('#fuentes').css('display', 'block');
});

</script>
	</div>
	
<div id='mapa'>

</div>
	<a href='#' style='display:block' id='geolocate'>¿Donde estoy?</a>
	
	
	<div id='LugarDiv'> 
<a class="close" href="#"></a>
		<p id='Nombre'> </p>
		<img src='' id="imagen" alt='Imagen del lugar'/>

		<div id='content'>
		<p id='Direccion'> </p>
		<p id='Descripcion'> </p>
<p id"pregunta">¿Que opinas de este lugar?</p>
	<form id='form_resp'>
	            <fieldset>
	                 <div class="preguntaRadio">
	                      <input type="radio" class="radio" name="respuesta" value="1" id="1"/>
	                      <label for="1">De puta madre</label>
	                      <input type="radio" class="radio" name="respuesta" value="2" id="2"/>
	                      <label for="2">Bàrbaro</label>
	                      <input type="radio" class="radio" name="respuesta" value="3" id="3"/>
	                      <label for="3">Bien</label> 
	                      <input type="radio" class="radio" name="respuesta" value="4" id="4"/>
	                      <label for="4">Hay mejor</label>
	                      <input type="radio" class="radio" name="respuesta" value="5" id="5"/>
	                      <label for="5">Andense</label>                                                                
	                 </div>
	            </fieldset>
	<input type="hidden" name="idLugar" id="idLugar">
	<input class='submit' value="¡He dicho!">
	</form>
	<p id='gracias'>¡Gracias por su contribucion!</p>
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
			$('.submit').click(dicho);
			
			function dicho()
			{
				$.ajax({
					url: 'agregar.php?respuesta=' + $('input:radio[name=respuesta]:checked').val() + '&idLugar=' + $.idLugar,
		                                success: function(data) {
		                                        $('#form_resp').hide();
		                                        $('#gracias').fadeIn('slow');
		                                }
				});
			}
		});	
	</script>
	        </div>	
<hr width='100%' color='#333'>
	<div id="Eventos">
	<h1 id="events">Events</h1>
		<div id='contenidoEvento'>
		
		</div>		
	</div>
	
</div>

</div>

<script>
$('div a.close').click(function(){
	$(this).parent().hide('fold');
	$('#form_resp').show();
    $('#gracias').hide();
});

$('div a.Exp_evento').click(function(){
	$('#expand').css('display', 'block');
});


	var mapa = mapbox.map('mapa');
	
	
	mapa.addLayer(mapbox.layer().id('baptistej.map-19wg5p9y'))
	mapa.centerzoom({ lat: -34.59958517636929, lon: -58.43636425665004 }, 12);
	
	// INICIO GEOLOCATION
	
	var geolocate = document.getElementById('geolocate');

	  // This uses the HTML5 geolocation API, which is available on
	  // most mobile browsers and modern browsers, but not in Internet Explorer
	  //
	  // See this chart of compatibility for details:
	  // http://caniuse.com/#feat=geolocation
	  if (!navigator.geolocation) {
	      geolocate.innerHTML = 'No es disponible con su navigador';
	  } else {
	      geolocate.onclick = function(e) {
	          e.preventDefault();
	          e.stopPropagation();
	          navigator.geolocation.getCurrentPosition(
	          function(position) {
	              // Once we've got a position, zoom and center the map
	              // on it, add ad a single feature
	              mapa.zoom(15).center({
	                  lat: position.coords.latitude,
	                  lon: position.coords.longitude
	              });
	              // And hide the geolocation button
	              geolocate.parentNode.removeChild(geolocate);
	          },
	          function(err) {
	              // If the user chooses not to allow their location
	              // to be shared, display an error message.
	              geolocate.innerHTML = 'No se puede encontrar su posición';
	          });
	      };
	  }
	
	
	
	
	// FIN GEOLOCATION
	
	
	
	mapa.ui.zoomer.add();
	$('#mapa').css('overflow', 'visible');



	// BICI
	
	 var biciLayer = mapbox.markers.layer().csv('lat,long,title,marker-color,marker-symbol\n-34.592308,-58.37501,RETIRO,#F0D400,bicycle\n-34.611365,-58.369077,ADUANA,#F0D400,bicycle\n-34.583669,-58.391243,DERECHO,#F0D400,bicycle\n-34.601599,-58.369421,PLAZA ROMA,#F0D400,bicycle\n-34.580224,-58.420751,PLAZA ITALIA,#F0D400,bicycle\n-34.628017,-58.369853,PARQUE LEZAMA,#F0D400,bicycle\n-34.606297,-58.381051,OBELISCO,#F0D400,bicycle\n-34.609829,-58.389289,CONGRESO,#F0D400,bicycle\n-34.58503,-58.407637,PARQUE LAS HERAS,#F0D400,bicycle\n-34.615974,-58.365666,UCA PUERTO MADERO,#F0D400,bicycle\n-34.601342,-58.385008,TRIBUNALES,#F0D400,bicycle\n-34.593367,-58.389698,PLAZA VICENTE LOPEZ,#F0D400,bicycle\n-34.609475,-58.407615,ONCE,#F0D400,bicycle\n-34.577468,-58.426158,PACIFICO,#F0D400,bicycle\n-34.622755,-58.388947,VIRREY CEVALLOS,#F0D400,bicycle\n-34.59902,-58.398217,PLAZA HOUSSAY,#F0D400,bicycle\n-34.609917,-58.374721,PLAZA DE MAYO,#F0D400,bicycle\n-34.605926,-58.419482,PLAZA ALMAGRO,#F0D400,bicycle\n-34.654286,-58.380429,CMD,#F0D400,bicycle\n-34.617908,-58.38047,INDEPENDENCIA,#F0D400,bicycle\n-34.594958,-58.378283,PLAZA SAN MARTIN,#F0D400,bicycle');

	  // Add interaction to this marker layer. This
	  // binds tooltips to each marker that has title
	  // and description defined.
	mapbox.markers.interaction(biciLayer);
		mapa.addLayer(biciLayer);

	//  FIN BICI

		
	
	//mapa.ui.attribution.add().content('<a href="http://mapbox.com/about/maps">Terms &amp; Feedback</a>');
	// INICION PHP
				<?php
					echo 'var features = [';
						$geo = '';
						$prop = '';
						$i = 0;
						$len = mysql_num_rows($result);
						while($row = mysql_fetch_array($result))
						{
							$count_events = $row['count']; 
							echo '{"geometry": {"type": "Point", "coordinates": ['. $row['Longitud'] .', ' .$row['Latitud'] .']},';
							if ($count_events != 0)
							{
								$color = '#006cb5';
							}
							else
							{
								$color = '#b54141';
							}
							if(++$i === $len) 
							{
								echo '"properties": { "Nombre":"' . str_replace('"', '', $row['Nombre']) .'", "Direccion":"' .str_replace('"', '', $row['Direccion']) .'", "Count": "' . $count_events .'", "Id":"' .$row['idLugar'].'", "marker-color":"'.$color.'", "marker-size": "medium"}}];';
							}
							else
							{
								echo '"properties": { "Nombre":"' . str_replace('"', '', $row['Nombre']) .'", "Direccion":"' . str_replace('"', '', $row['Direccion']) .'", "Count": "' . $count_events .'", "Id":"' .$row['idLugar'].'", "marker-color":"'.$color.'", "marker-shape": "pin",  "marker-size":"medium", "z-index":"0"}},';
							}
						
						}
						?>
					
					// FIN PHP
										
				// Create and add marker layer
				    var markerLayer = mapbox.markers.layer().features(features);
				// .factory(function(f) {
				// 						    // Define a new factory function. This takes a GeoJSON object
				// 						    // as its input and returns an element - in this case an image -
				// 						    // that represents the point.
				// 						        var img = document.createElement('img');
				// 						        img.className = 'marker-image';
				// 						        img.setAttribute('src', 'marker.svg');
				// 						        return img;
				// 						    });
				    var interaction = mapbox.markers.interaction(markerLayer);
				
				    mapa.addLayer(markerLayer);

				    // Set a custom formatter for tooltips
				    // Provide a function that returns html to be used in tooltip
				    interaction.formatter(function(feature) {
					var d = new Date();
					var month = d.getMonth() + 1;  
					var hoy = d.getFullYear() + '-' + month + '-' + d.getDate();
						$.ajax({
							type:'GET',
							url : "http://pipes.yahoo.com/pipes/pipe.run?_id=9cd7fcd9e68ac27123e7cf4f4a0686b6&_render=json&idLugar=" + feature.properties.Id + '&hoy=' + hoy + '&_callback=?',
							async: false,
							dataType : "jsonp",
							success : function (parsed_json) {
								displayEventos(parsed_json, feature);
							},
						});
					
				        // var o = '<a target="_blank" href="' + feature.properties.url + '">' +
				        // 				            // '<img src="' + feature.properties.image + '">' +
				        // 				            '<h2>' + feature.properties.Nombre + '</h2>' +
				        // 				            '</a>';
					
								if (feature.properties.Count > 1)
								{
								var o = '<h1 class="marker-title">' + feature.properties.Nombre +' </h1> <div class="marker-description">' + feature.properties.Direccion + '</div><p>'+ feature.properties.Count +' eventos ahi</p><a onclick="displayInfo('+ feature.properties.Id + ')" class="lugarInfo" href="#'+ feature.properties.Id + '">Más>></a>';
								}
								else if (feature.properties.Count == 1)
								{
									var o = '<h1 class="marker-title">' + feature.properties.Nombre +' </h1> <div class="marker-description">' + feature.properties.Direccion + '</div><p>'+ feature.properties.Count +' evento ahi</p><a onclick="displayInfo('+ feature.properties.Id + ')" class="lugarInfo" href="#'+ feature.properties.Id + '">Más>></a>';
								}
								else
								{
								var o = '<h1 class="marker-title">' + feature.properties.Nombre +' </h1> <div class="marker-description">' + feature.properties.Direccion + '</div><p>No hay evento planificado ahi</p><a onclick="displayInfo('+ feature.properties.Id + ')" class="lugarInfo" href="#'+ feature.properties.Id + '">Más>></a>';
								}
						return o;
						
				    });


				
				interaction.showOnHover(false);
				interaction.exclusive(true);

					function displayEventos (parsed_json, feature) {
						console.log(parsed_json);

						$.cuanto = parsed_json['count'];
						console.log('count'+ $.cuanto);						
							$('#contenidoEvento').empty();				
						if ($.cuanto != 0)
						{
							
						for (var i=0; i< $.cuanto; i++)
						{
						$.titulo = parsed_json['value']['items'][i]['Titulo'];
						$.idevento = parsed_json['value']['items'][i]['IdEvento'];
						$.descripcion = parsed_json['value']['items'][i]['Descripcion'];
						$.fechaInicio = 'Desde: '+parsed_json['value']['items'][i]['FechaInicio'];
						$.fechaFin = 'Hasta el: '+parsed_json['value']['items'][i]['FechaFin'];
						$.hora = parsed_json['value']['items'][i]['Hora'];
						$.minutos = parsed_json['value']['items'][i]['Minutos'];
						if ($.minutos == '0') $.minutos = '00';
						$.EImagen = parsed_json['value']['items'][i]['Imagen'];
						$.ENombreUrl = parsed_json['value']['items'][i]['NombreUrl'];
						$.contenido = "";
						$.contenido += "<a class='Exp_evento' href='#'><div class='Evento'><h2 id='titulo'>"+ $.titulo +" </h2></a>";
						$.contenido += "<div id='expand'>"
						$.contenido += "<img src='http://fotos.agendacultural.buenosaires.gob.ar/"+$.ENombreUrl +"/"+ $.EImagen + "' id='EImagen' alt='Imagen del evento'/>";
						$.contenido += "<p id='descripcion'>"+ $.descripcion +" </p>";
						$.contenido += "<p id='FechaInicio'>" + $.fechaInicio + " a las " + $.hora + ":" + $.minutos +"</p>";
						$.contenido += "<p id='FechaFin'>"+ $.fechaFin +" </p></div></div>";
						$('#contenidoEvento').append($.contenido);
						var alta = $('#EImagen').height();
						console.log(alta);
						alta = alta + 30;
						$('.Evento').css('min-height', alta);
						}
					}
						else {
							$.titulo = '';
							$.idevento = '';
							$.descripcion = 'No hay evento por venir en este lugar. <br/> Aun asi, podes compartir este lugar o jugarlo.';
							$.fechaInicio = '';
							$.fechaFin = '';
							$.EImagen = '';
							$.ENombreUrl = '';
							$.contenido = '';
							$.contenido += "<div class='Evento'><h2 id='titulo'>"+ $.titulo +" </h2>";
							$.contenido += "<img src=''/>";
							$.contenido += "<p id='descripcion'>"+ $.descripcion +" </p></div>";
							$('#contenidoEvento').html($.contenido);
						}
					}

				function displayInfo(idLugar){
					$.ajax({
						type:'GET',
						url : "http://pipes.yahoo.com/pipes/pipe.run?_id=4c9158c505beb2dbef894be996890b04&_render=json&idLugar=" + idLugar +'&_callback=?',
						async: false,
						dataType : "jsonp",
						success : function (parsed_json) {
							fetchLugar(parsed_json);
							console.log(parsed_json);
						},
					});

					$('#LugarDiv').slideUp().show('fold');
				};

 				function fetchLugar (parsed_json) {
					$.LNombre = parsed_json['value']['items'][0]['Item']['Nombre'];
					$.LDireccion = parsed_json['value']['items'][0]['Item']['Direccion'];
					$.LDescripcion = parsed_json['value']['items'][0]['Item']['Descripcion'];
					$.LImagen = parsed_json['value']['items'][0]['Item']['Imagen'];
					$.LNombreUrl = parsed_json['value']['items'][0]['Item']['NombreUrl'];
					$.idLugar = parsed_json['value']['items'][0]['Item']['IdLugar'];
					$('#idLugar').attr('value', $.idLugar);
					console.log($.LNombreUrl);
					$('#Nombre').html($.LNombre);
					$('#Direccion').html($.LDireccion);
					$('#Descripcion').html($.LDescripcion);
					$('#imagen').attr('src', 'http://fotos.agendacultural.buenosaires.gob.ar/'+$.LNombreUrl +'/'+ $.LImagen);
	
}


	
</script>
<?php
mysql_close($dbhandle);


?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36370081-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>