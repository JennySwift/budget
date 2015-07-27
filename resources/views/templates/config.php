<?php
	$app = app_path();
	$public = public_path();

	$templates = base_path() . '/resources/views/templates';
	$head_links = $templates . '/head-links.php';
	$header = $templates . '/header.blade.php';
	$tools = $public . '/tools';
	$css = $public . '/css';
	$ajax = $app . '/ajax';

    define("APP", app_path());
    define("INC", app_path() . '/inc');

?>