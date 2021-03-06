<?php

include 'SimpleImage.php';
include 'DBHandler.php';

function store_uploaded_image($file, $target_file, $max_img_dimens) {

	$image = new SimpleImage();
	$image->load($file);
	if ($image->getWidth() > $max_img_dimens) {
		$image->resizeToWidth($max_img_dimens);
	}
	if ($image->getHeight() > $max_img_dimens) {
		$image->resizeToHeight($max_img_dimens);
	}
	$image->save($target_file,$image->image_type);
	return $target_file;

}


$charLimit = 2000;

// Did the user submit a message
if (isset($_POST['text'])) {
	if ($_POST['text'] != '') {
		if (strlen($_POST['text']) <= $charLimit) {
			$handler->postMessage();
		} else {
			echo "Message exceeds 2000 character limit.";
			die();
		}
	}
	// Reset
	return '200 OK';
}

?>

<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="An open ledger for posting anonymous public messages">
    <link rel="stylesheet" type="text/css" href="../../assets/css/shared.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>OpenChat - puffyboa.xyz</title>
	<link rel="shortcut icon" href="../../assets/img/favicon.png" />
</head>

<body>

    <div class="back-to-home">
        <a href="../../index.html">puffyboa.xyz</a>
        <a href="index.php">OpenChat</a>
    </div>

	<section id="jumbo">
		<h1>OpenChat</h1>
		<p>welcome to openchat, an open ledger for posting anonymous public messages</p>
	</section>

	<section id="main">

		<form id="form" enctype="multipart/form-data" onsubmit="return sendMessage(this);">
		    <input type="text" name="text" placeholder="type something" maxlength="1000" required="required">
		    <input name="userfile" accept="image/*" type="file" />
		    <input type="submit">
		</form>

		<div id="ledger">
		</div>

	</section>

</body>
</html>

<script src="script.js"></script>
