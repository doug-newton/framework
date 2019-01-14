<?php

setTemplateName("_blank");

$panelSource = "/page?name=404";
$panelId = "not-found";

ob_start();
try {
?>
<?
} catch(\Exception $e) {
?>
	<!--	custom css exception: <?=$panelId?>	-->
<?php
}
?>
<?php
addUniqueCustomJs(ob_get_clean(), $panelId);

ob_start();
try {
?>
<?
} catch(\Exception $e) {
?>
	<!--	custom javascript exception: <?=$panelId?>	-->
<?php
}
addUniqueCustomCss(ob_get_clean(), $panelId);

?>
<div id="<?=$panelId?>" class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h1>page not found</h1>
			<p>sorry, the page you're looking for can't be found. we've looked everywhere!</p>
		</div>
	</div>
</div>
