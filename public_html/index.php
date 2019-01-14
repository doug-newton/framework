<?php

require_once 'vendor/autoload.php';

use Core\Router;

/*	used by templates and the exception box below */

$bPlain = false;

function setPlain($plain) {
	global $bPlain;
	$bPlain = $plain;
}

function getPlain() {
	global $bPlain;
	return $bPlain;
}

$pageTop = "";

function getPageTop() {
	global $pageTop;
	return $pageTop;
}

function setPageTop($html) {
	global $pageTop;
	$pageTop = $html;
}

$pageBottom = "";

function getPageBottom() {
	global $pageBottom;
	return $pageBottom;
}

function setPageBottom($html) {
	global $pageBottom;
	$pageBottom = $html;
}

$htmlBuffer = "";

function getHtmlBuffer() {
	global $htmlBuffer;
	return $htmlBuffer;
}

function setHtmlBuffer($buffer) {
	global $htmlBuffer;
	$htmlBuffer = $buffer;
}

/*
 * these arrays allow for an hierarchical/vue-like page structure
 * (modularising html, css and js in a single file)
 */

$customJsArray = [];
$uniqueCustomJsArray = [
];

/* the same constructs, but for styles */

$customCssArray = [];
$uniqueCustomCssArray = [
];

function addCustomJs($js) {
	global $customJsArray;
	$customJsArray[] = $js;
}

/*
 * ensure that js script is included only once
 */

function addUniqueCustomJs($js, $label) {
	global $customJsArray, $uniqueCustomJsArray;
	if (!isset($uniqueCustomJsArray[$label])) {
		$uniqueCustomJsArray[$label] = true;
		ob_start();
?>
<!--	custom js: "<?=$label?>"	-->
<?php
		echo $js;
		addCustomJs(ob_get_clean());
	}
}

function addCustomCss($css) {
	global $customCssArray;
	$customCssArray[] = $css;
}

/*
 * ensure that stylesheet is included only once
 */

function addUniqueCustomCss($css, $label) {
	if (!isset($uniqueCustomJsArray[$label])) {
		$uniqueCustomJsArray[$label] = true;
		ob_start();
?>
<!--	custom css: "<?=$label?>"	-->
<?php
		echo $css;
		addCustomCss(ob_get_clean());
	}
}


function setTemplateName($template) {
	global $templateName;
	$templateName = $template;
}

function exceptionBox($message) {
	?>
	<div class="alert alert-danger">
	  <h4 class="alert-heading">Exception Caught!</h4>
	  <p class="mb-0"><?=$message?></p>
	</div>
	<?php
}

require __DIR__ . '/routes.php';

/*	
 *
 *	$pageBottom will either contain the desired html
 *	or the exception box if something goes wrong
 *
 *
 */

		try {
			ob_start();
				Router::handleRequest();
			$htmlBuffer = ob_get_clean();
		} catch (\Exception $e) {
			$htmlBuffer = exceptionBox($e->getMessage());
		}

		//	$htmlBuffer will always have something in at at this stage

		ob_start();

		try {

			if (getPlain() == true) {
				require_once __DIR__ . "/templates/_blank.php";
			} else {

				switch ($templateName) {

					/*	put all template names here,
					 *	e.g. case 'my_template';
					 *	of course, this requires that there's a 
					 *		"/templates/my_template.php"
					 */
					
					case '_blank':
						require_once __DIR__ . "/templates/$templateName.php";
						break;

						/* 
						 *
						 * if the template wasn't found, then a fallback
						 * template must be "require"d
						 *
						 * currently the default is _blank but it should probably
						 * go in a variable
						 *
						 *
						 */

					case '':
						throw new \Exception("critical: no template defined!");
						break;

					default:
						throw new \Exception("critical: no such template \"$templateName\"!");
						break;
				}

			}


		} catch (\Exception $e) {

			/*	
			 *	rewrite the $htmlBuffer, since something went wrong and anything
			 *	in the $htmlBuffer would probably be invalidly formatted
			 */

			ob_start();
				exceptionBox($e->getMessage());
			setHtmlBuffer(ob_get_clean());

			//	default template
			//	if an exception was thrown, then no template was used.
			//	therefore use the bare minimum so that the exception
			//	displays on the page

			require_once __DIR__ . "/templates/_blank.php";
		}
		$pageBottom = ob_get_clean();

		//	$pageBottom will always have something in it at this stage


require_once __DIR__ . "/final-render.php";

?>
