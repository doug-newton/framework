<?php

/*
	global 
		$pageTitle, 
		$pageHeading, 
		$templateName,
		$customCssArray, 
		$customJsArray,
		$sideMenuLeft,
		$sideMenuRight;
 */


use Core\Router;

Router::setDefault(function() {
	require __DIR__ . '/pages/404.php';
});

Router::register([

	['GP', 'page', function() {

		global $pageTitle, 
			$pageHeading, 
			$templateName,
			$customCssArray, 
			$customJsArray,
			$sideMenuLeft,
			$sideMenuRight,
			$bPlain;

		if (isset($_GET['name'])) {
			$page_name = $_GET['name'];
		}

		require __DIR__ . '/page-routes-test/test/test.php';
	}],

	['GP', 'foo', function() {
		echo "sdfsdf";
	}],

	/*
	 * TODO must become
	 * ['G', 'data' function(){}]
	 * and
	 * ['P', 'data' function(){}]
	 */

	['G', 'data', function() {

		global $pageTitle, 
			$pageHeading, 
			$templateName,
			$customCssArray, 
			$customJsArray,
			$sideMenuLeft,
			$sideMenuRight,
			$bPlain;

		setPlain(true);

		if (isset($_GET['name'])) {
			$page_name = $_GET['name'];
		}

		require __DIR__ . '/get-data/member.php';
	}],

	/*
	* todo change to
	 * ['P', 'data' function(){}]
	 */
	['G', 'putdata', function() {

		global $pageTitle, 
			$pageHeading, 
			$templateName,
			$customCssArray, 
			$customJsArray,
			$sideMenuLeft,
			$sideMenuRight,
			$bPlain;

		setPlain(true);

		if (isset($_GET['name'])) {
			$page_name = $_GET['name'];
		}

		require __DIR__ . '/put-data/member.php';
	}]

]);


?>
