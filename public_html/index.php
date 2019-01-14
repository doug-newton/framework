<?php

require_once 'vendor/autoload.php';

use Core\Router;

/*	used by templates and the exception box below */

class Document {

	/*	whether to render the page with a template/
	 *
	 *	will use a template, namely templates/_blank.php
	 */

	protected $bPlain = false;

	public function setPlain($plain) {
		$this->bPlain = $plain;
	}

	public function getPlan() {
		return $this->bPlain;
	}

	/*
	 * the actual <title>
	 *
	 * used within templates
	 * note that templates can use $this->setPageTitle and $this->getPageTitle
	 * because they are <i>require</i>d from within a Document method
	 */

	protected $pageTitle = "Business Gamechangers";

	public function setPageTitle($title) {
		$this->pageTitle = $title;
	}

	public function getPageTitle($title) {
		return $this->pageTitle;
	}

	/*
	 * the top of the page, from the beginning of the
	 * opening html tag to just before the closing
	 * head tag
	 */

	protected $pageTop = "";

	public function getPageTop() {
		global $pageTop;
		return $pageTop;
	}

	public function setPageTop($html) {
		global $pageTop;
		$pageTop = $html;
	}

	/*
	 * the bottom of the page
	 * everything from just after the opening body tag
	 * to the bottom of the html document
	 *
	 * built in the template from the $htmlBuffer plus
	 * any template-specific javascript code and / or libraries
	 */

	protected $pageBottom = "";

	public function getPageBottom() {
		global $pageBottom;
		return $pageBottom;
	}

	public function setPageBottom($html) {
		global $pageBottom;
		$pageBottom = $html; 
	}

	/*
	 * everything inside the body tags except for the
	 * custom scripts for the specific page, and the
	 * scripts specific to the template (which are 'manually
	 * inserted') when constructing the $pageBottom
	 */

	protected $htmlBuffer = "";

	public function getHtmlBuffer() {
		global $htmlBuffer;
		return $htmlBuffer;
	}

	public function setHtmlBuffer($buffer) {
		global $htmlBuffer;
		$htmlBuffer = $buffer;
	}

	/*
	 * these arrays allow for an hierarchical/vue-like page structure
	 * (modularising html, css and js in a single file)
	 */

	protected $customJsArray = [];
	protected $uniqueCustomJsArray = [
	];

	protected $customCssArray = [];
	protected $uniqueCustomCssArray = [
	];

	public function addCustomJs($js) {
		global $customJsArray;
		$customJsArray[] = $js;
	}

	public function addUniqueCustomJs($js, $label) {
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

	public function addCustomCss($css) {
		global $customCssArray;
		$customCssArray[] = $css;
	}

	public function addUniqueCustomCss($css, $label) {
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

	/*
	 * the name of the template, as determined by the page script
	 */

	protected $templateName;

	public function setTemplateName($templateName) {
		$this->templateName = $templateName;
	}

	public function getTemplateName() {
		return $this->templateName;
	}

	public function exceptionBox($message) {
?>
	<div class="alert alert-danger">
	  <h4 class="alert-heading">Exception Caught!</h4>
	  <p class="mb-0"><?=$message?></p>
	</div>
<?php
	}

	/*
	 * render the inner content of the page into $htmlBuffer,
	 * at the same time populating custom scripts and styles required for
	 * the page
	 *
	 * the custom scripts and styles arrays are used when creating the
	 * $pageBottom, just after any scripts and styles specific
	 * to the encasing template
	 */

	public function processHtml() {

		ob_start();
		Router::handleRequest();
		$this->setHtmlBuffer(ob_get_clean());
	}

	/*
	 * use the outcome of processHtml() (namely $htmlBuffer, $pageTop,
	 * $pageBottom, $templateName, $bPlain)
	 *
	 * to either render the innerhtml as part of a requested template,
	 * or by itself (as if the rest of the page surrounded it). the latter
	 * is used by ajax, by appending the url with &plain=1
	 */

	public function processTemplate() {

		$templateName = $this->getTemplateName();

		require __DIR__ . "/templates/$templateName.php";
		
		echo $this->getPageBottom();

	}

	public function finalRender() {
		require __DIR__ . '/final-render.php';
?>
<?php
	}

	public function insertCustomCss() {
		foreach ($this->customCssArray as $css) {
			echo $css;
		}
	}

	public function insertCustomJs() {
		foreach ($this->customJsArray as $js) {
			echo $js;
		}
	}
}

$document = new Document();

Router::setDefault(function() {
});

Router::register([
	['G', '', function() {
		global $document;
		$document->setTemplateName("_blank");
		echo "Hello world";
	}]
]);

function renderPage() {
	global $document;
	$document->processHtml();
	$document->processTemplate();
}

renderPage();

