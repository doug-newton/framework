<?php

setTemplateName("_blank");

$panelSource = "pages?name=test/_blank";
$panelId = "test-_blank";

/*

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

 */

?>
<div class="ajax-panel" id="<?=$panelId?>">
	<a class="source" href="<?=$panelSource?>"></a>
	<!-- span class="ajax-arguments" data-for="<?=$panelId?>" data-team-id="<?=$teamId?>" ></span -->
	<h1>_blank</h1>
</div>
