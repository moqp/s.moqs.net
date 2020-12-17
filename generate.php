<?php
$url = $_GET['url'];
$recaptcha = htmlspecialchars($_GET["response"], ENT_QUOTES, 'UTF-8');

if (isset($recaptcha)) {
	$captcha = $recaptcha;
} else {
	$captcha = "";
	print '{
	"response": false,
	"url": null
}';
	exit;
}

// Google reCAPTCHA シークレットキー
$secretKey = "";

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
$resp_result = json_decode($response, true);

// Google reCAPTCHA validation に成功した場合
if (intval($resp_result["success"]) == 1) {
	// URL が正しいものか確認
	if (preg_match('/https?:\/{2}[\w\/:%#\$&\?\(\)~\.=\+\-]+/', $url)) {
		$code = codeGenerate();

		// URL データファイルを作成
		file_put_contents('./UrlData/' . $code . '.url', $url);

		// 結果の出力
		print '{
	"response": true,
	"url": "https://s.moqs.net/' . $code . '"
}';	
		return;
	}	
}

// 失敗した場合の結果出力
print '{
	"response": false,
	"url": null
}';

// 短縮 URL ID の生成
function codeGenerate() {
	$code = v4();
	$code = explode('-', $code)[1];

	// 被った場合は再生成
	if (file_exists('./UrlData/' . $code . '.url')) {
		$code = codeGenerate();
	}

	return $code;
}

// UUID v4 の生成
function v4() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0x0fff) | 0x4000,
		mt_rand(0, 0x3fff) | 0x8000,
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	);
}

?>
