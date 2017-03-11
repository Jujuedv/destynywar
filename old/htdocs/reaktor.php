<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["reaktor"]["Name"]}");
	if($buildings["reaktor"]["Stufe"]){
		$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
		if(is_numeric($_GET['vh']) && $_GET['vh'] <= 100 && $_GET['vh'] >= 0){
			$buildings["reaktor"]["verhaeltnis"] = $_GET['vh'];
			mysql_query("UPDATE `planets` SET `reaktorplasmaverhaeltnis` = {$_GET['vh']} WHERE `planets`.`id` = {$_SESSION['planetid']}");
		}
?>
<h1><?=$buildings["reaktor"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["reaktor"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<h3>Plasma:</h3><br/>
<table>
<tr><td><b>Zurzeitige Produktion(Stufe <?=$buildings["reaktor"]["Stufe"]?>):</b></td><td><b id="pp"><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]]*($buildings["reaktor"]["verhaeltnis"])/50*$settings['speed'])*$out->moral?></b></td></tr>
<tr><td><b>Produktion der nächsten Stufe(<?=$buildings["reaktor"]["Stufe"]+1?>):</b></td><td><b id="ppN"><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]+1]*($buildings["reaktor"]["verhaeltnis"])/50*$settings['speed'])*$out->moral?></b></td></tr>
</table><br/><br/>
<h3>Energie:</h3><br/>
<table>
<tr><td><b>Zurzeitige Produktion(Stufe <?=$buildings["reaktor"]["Stufe"]?>):</b></td><td><b id="ep"><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]]*(100-$buildings["reaktor"]["verhaeltnis"])/50*$settings['speed'])*$out->moral?></b></td></tr>
<tr><td><b>Produktion der nächsten Stufe(<?=$buildings["reaktor"]["Stufe"]+1?>):</b></td><td><b id="epN"><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]+1]*(100-$buildings["reaktor"]["verhaeltnis"])/50*$settings['speed'])*$out->moral?></b></td></tr>
</table>
<style type="text/css">
  div.slider { width:256px; margin:10px ; background-color:#ccc; height:10px; position: relative; }
  div.slider div.handle { width:10px; height:15px; background-color:#f00; cursor:move; position: absolute; }
  div#zoom_element { width:50px; height:50px; background:#2d86bd; position:relative; }
</style></br></br>
<h3>Verh&auml;ltnis:</h3>
mehr&nbsp;Energie&nbsp;<&nbsp;&nbsp;&nbsp;>&nbsp;mehr&nbsp;Plasma
<div id="vh_slider" class="slider">
    <div id="handle" class="handle"></div>
</div>
<a href="#" onclick="Save();">Speichern</a>
<script type="text/javascript">
var val = <?=$buildings["reaktor"]["verhaeltnis"]?>,
pnow = <?=$buildings["produktion"][$buildings["reaktor"]["Stufe"]]*$settings['speed']*$out->moral?>,
pnext = <?=is_numeric($buildings["produktion"][$buildings["reaktor"]["Stufe"]+1])?$buildings["produktion"][$buildings["reaktor"]["Stufe"]+1]*$settings['speed']*$out->moral:"null"?>;

 (function() {
    var vh_slider = $('vh_slider');
	new Control.Slider($('handle'), $('vh_slider'), {
      range: $R(0, 100),
      sliderValue: <?=$buildings["reaktor"]["verhaeltnis"]?>,
      onSlide: function(value) {
		val = value;
		$('pp').innerHTML = Math.floor(Math.floor(val)/50*(pnow));
		$('ppN').innerHTML = Math.floor(Math.floor(val)/50*(pnext));
		$('ep').innerHTML = Math.floor(Math.floor(100-val+0.999)/50*(pnow));
		$('epN').innerHTML = Math.floor(Math.floor(100-val+0.999)/50*(pnext));
      },
      onChange: function(value) { 
		val = value;
		$('pp').innerHTML = Math.floor(Math.floor(val)/50*(pnow));
		$('ppN').innerHTML = Math.floor(Math.floor(val)/50*(pnext));
		$('ep').innerHTML = Math.floor(Math.floor(100-val+0.999)/50*(pnow));
		$('epN').innerHTML = Math.floor(Math.floor(100-val+0.999)/50*(pnext));
      }
    }); 
})();
	function Save(){
		document.location.href = "reaktor.php?vh="+Math.floor(val);
	}

</script>
<?php 
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>