<?php
$id = $_SERVER["REQUEST_URI"];
if (startsWith($id, '/')) {
	$id = substr($id, 1);
}
if (file_exists('./UrlData/' . $id . '.url')) {
	$url = file_get_contents('./UrlData/' . $id . '.url');
	header('Location: ' . $url);
	exit;
} else {
  header('Location: https://s.moqs.net');
  exit;
}

function startsWith($haystack, $needle) {
    return (strpos($haystack, $needle) === 0);
}
?>
