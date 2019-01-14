<?php

/*	
 *	take values from the previous template and structure them into
 *	a page generically:
 *		-	without hard-coded css
 *		-	without hard-coded javascript
 *
 * 	the stylesheet and javascript libraries needed to make the
 * 	page function are defined by the template,
 * 	when setting $pageTop (wasteful to append $cusomCssArray)
 *
 * 	-	note that $customCssArray/JsArray are appended to by pages,
 * 		i.e. the scripts that populate the $htmlBuffer as the
 * 		result of the Router::handleRequest() callback
 *
 *	--------------------------------------------------------
 *	the necessity of final-render.php arises from
 *	needing a "second pass-through"
 *
 *	this is because required files append to the
 *	$customCssArray global, but since they are part of the
 *	body of the document, they would only specify their
 *	styles AFTER head, which is where the styles need to be
 *
 *	$htmlTop - head tags and resource links
 *	$htmlBottom - $htmlBuffer formatted into page
 *
 *	the $htmlBuffer is irrespective of its surrounding template
 *
 *	the template inserts it into $htmlBottom, which is respective
 *	of the surrounding template. at the same time, all required
 *	scripts append to the $customCssArray
 *
 * 	all required pages are therefore guaranteed by final-render.php
 * 	to have both their custom styles and scripts rendered
 * 	to the page
 */

echo getPageTop();
foreach ($customCssArray as $css) {
	echo $css;
}
?>
</head>
<body>
<?php
echo getPageBottom();
foreach ($customJsArray as $js) {
	echo $js;
}
?>
</body>
</html>
