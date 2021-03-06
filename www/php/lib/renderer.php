<?php

# This file is part of CoolStart.net.
#
#	 CoolStart.net is free software: you can redistribute it and/or modify
#	 it under the terms of the GNU Affero General Public License as published by
#	 the Free Software Foundation, either version 3 of the License, or
#	 (at your option) any later version.
#
#	 CoolStart.net is distributed in the hope that it will be useful,
#	 but WITHOUT ANY WARRANTY; without even the implied warranty of
#	 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#	 GNU Affero General Public License for more details.
#
#	 You should have received a copy of the GNU Affero General Public License
#	 along with CoolStart.net.  If not, see <http://www.gnu.org/licenses/>.



// this functions returns the base html
function render_wrapper($title = 'Homepage - CoolStart.net', $content, $compress = false){
	require_once __DIR__.'/../config.php';
	
	ob_start();
?>

<!doctype html>
<html>
<head>
	<title><?=$title?></title>
	<link rel="stylesheet" href="//<?=WEB_PATH?>css/reset.min.css"/>
	<link rel="stylesheet" href="//<?=WEB_PATH?>css/renderer.css"/>
</head>
<body>
	<div class="wrapper">
		<div class="row">
			<div class="widgets" id="widgets0"></div>
		</div>
		
		<div class="row">
			<div class="false_bottom_bar"></div>
		</div>
		<div class="bottom_bar" id="bottom_bar"></div>
	</div>
	
	<script src="//<?=WEB_PATH?>js/crel2.js"></script>
	<script src="//<?=WEB_PATH?>js/api.js"></script>
	<script><?=ANALYTICS_JS?></script>

	<script>
		// generate the links of the bottom menu bar
		(function(){
			var C = crel2;
			
			var menu = document.getElementById("bottom_bar");
			
			C(menu
				,C("a", ["href", "//<?=WEB_PATH?>", "class", "btn"], "Home")
				<?php if (G::$SESSION->exists()) { ?>
					,C("a", ["href", "//<?=WEB_PATH?>manage-widgets", "class", "btn"], "Manage widgets")
					,C("a", ["href", "//<?=WEB_PATH?>options", "class", "btn"], "Options")
				<?php }?>
				,C("a", ["href", "//<?=WEB_PATH?>developers", "class", "btn"], "Developers")
				,C("a", ["href", "http://<?=FORUM_WEB_PATH?>", "class", "btn", "target", "_blank"], "forum")
				,C("a", ["href", "//<?=WEB_PATH?>help", "class", "btn"], "help")
				,C("a", ["href", "//<?=WEB_PATH?>about", "class", "btn"], "about")
				<?php if (!G::$SESSION->exists()) { ?>
					,C("a", ["href", "//<?=WEB_PATH?>example", "class", "btn"], "View example")
				<?php }?>
				,C("a", ["href", "https://github.com/forestrf/CoolStartNet", "class", "btn", "target", "_blank"], "GitHub")
				<?php if (G::$SESSION->exists()) { ?>
					,C("a", ["href", "//<?=WEB_PATH?>user?action=logout", "class", "btn"], "Logout")
				<?php }?>
			);
		})();
	</script>
	
	<?=$content?>
	
	</body>
</html>

<?php
	$html = ob_get_contents();
	ob_end_clean();

	if ($compress) {
		require_once __DIR__.'/minify/min/lib/Minify/HTML.php';
		require_once __DIR__.'/minify/min/lib/Minify/CSS.php';
		require_once __DIR__.'/minify/min/lib/Minify/CSS/Compressor.php';
		//require_once __DIR__.'/minify/min/lib/JSMin.php';
		require_once __DIR__.'/minify/JSHrink/src/Minifier.php';

		$html = Minify_HTML::minify($html, array(
			'cssMinifier' => array('Minify_CSS', 'minify'),
			//'jsMinifier' => array('JSMin', 'minify')
			'jsMinifier' => array('JShrink\Minifier', 'minify')
		));
	}

	return $html;
}

// This function generates the full html page of the user page. It can be cached
function render(DB &$db, $compress = false){
	require_once __DIR__.'/../config.php';

	ob_start();
?>

<script id="delete_me">
	// remove innerHTML from the script to delete the secrets of each widget to prevent the manipulation of private variables of a widget from other widgets
	var t = document.getElementById("delete_me");
	t.parentNode.removeChild(t);
	delete t;
	
	(function(){
		// Make a copy of window.API to prevent modifications from widgets to the api used to construct other widgets
		var API = API_GENERATOR();

		// Variables for the config widget
		var CONFIG = [];

		<?php

		// Widgets del usuario
		$widgets_usuario = $db->get_widgets_user();
		foreach($widgets_usuario as &$widget){
			// Create the html that will call the script
			//echo "<script src=\"widgetfile.php?widgetID={$widget['IDwidget']}&widgetVersion={$version}&name=main.js\"></script>";
			$data = $db->get_widget_file($widget['IDwidget'], 'main.js');
			if (!$data) {
				continue;
			}
			?>

			(function(API){
				API = API.init("<?=$widget['IDwidget'];?>",
						"<?=hash_api(G::$SESSION->get_user_random(), $widget['IDwidget'], PASSWORD_TOKEN_API)?>",
						<?=server_vars_js();?>);



				<?php
					readfile($db->get_widget_file_path_from_hash($data['hash']));
				?>

				if(typeof CONFIG_function !== 'undefined'){
					CONFIG.push({
						'name':'<?=str_replace("'", "\\'", $widget['name']);?>',
						'function':CONFIG_function
					});
				}
			})(API);
			
		<?php }	?>
	})();
</script>

<?php
	$html = ob_get_contents();
	ob_end_clean();

	return render_wrapper('Homepage - CoolStart.net', $html, $compress);
}
?>
