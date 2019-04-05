<?php

if (!isset($url))           { $url = \App\Vars\Utils::getURL(); }
if (!isset($title))         { $title = $_ENV['WEB_TITLE']; }
if (!isset($description))   { $description = $_ENV['WEB_DESCRIPTION']; }
if (!isset($keywords))      { $keywords = $_ENV['WEB_KEYWORDS']; }
if (!isset($image_share))   { $image_share = $_ENV['HOST'] .'/img/share.png'; }

// $image_size = getimagesize($image_share);

?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="theme-color" content="#1e97ce">
<meta name="author" content="Educa Studio">
<meta name="title" content="<?=$title?>">
<meta name="description" content="<?=$description?>">
<meta name="keywords" content="<?=$keywords?>">

<meta property="og:locale"             content="id_ID" />
<meta property="og:url"                content="<?=$url?>" />
<meta property="og:site_name"          content="GameLab.ID" />
<meta property="og:type"               content="website" />
<meta property="og:title"              content="<?=$title?>" />
<meta property="og:description"        content="<?=$description?>" />
<meta property="og:image"              content="<?=$image_share?>" />
<?php if (!empty($_ENV['FB_APP_ID'])): ?>
	<meta property="fb:app_id"             content="<?=$_ENV['FB_APP_ID']?>" />
<?php endif; ?>

<link rel="icon" href="/favicon.ico?v=0" />

<title><?=$title?></title>