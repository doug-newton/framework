<?php

setTemplateName("_blank");

$panelSource = "pages?name=test/foo";
$panelId = "test-foo";

ob_start();
?>
<script>
</script>
<?php
addUniqueCustomJs(ob_get_clean(), $panelId);

ob_start();
?>
<style>
</style>
<?
addUniqueCustomCss(ob_get_clean(), $panelId);

?>
<div class="ajax-panel" id="<?=$panelId?>">
	<a class="source" href="<?=$panelSource?>"></a>
	<h1><?=$panelId?></h1>
</div>
