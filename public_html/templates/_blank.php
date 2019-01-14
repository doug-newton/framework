<?php

#	/-----------------------------------\
#	|-- page top -----------------------|
#	|header and template-specific styles|
#	\-----------------------------------/


$pageTitle = "_blank";

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?=$pageTitle?></title>
		<link rel="stylesheet" href="/css/flatly.min.css">
<?php setPageTop(ob_get_clean());

#	/-----------------------------------\
#	|--- page bottom -------------------|
#	|inner content arranged in template |
#	\-----------------------------------/

#	note on $pageBottom

#	whatever gets echoed here IS the $pageBottom
#	the requiring of this template is wrapped in an output buffer
#	
#	however, the requirer of this template may handle an exception
#	and set the $pageBottom as an exception box in place of what would
#	have been there

#	final-render is "require"d by the requiree of this template

#	/-----------------------------------\
#	|--- final render ------------------|
#	|header and template-specific styles|
#	\-----------------------------------/
