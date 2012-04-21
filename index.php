<?php

$file = str_replace('/mounted-storage', '' ,str_replace('\\', '/', str_replace('index.php', '', __FILE__)));
$uri = str_replace($file, '', array_shift(explode('?', str_replace('\\', '/', substr($_SERVER['DOCUMENT_ROOT'], 0, -1) . $_SERVER['REQUEST_URI']))));

include('src/Application.php');
$app = new Application($uri);
$page = $app->getPage();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="http://<?php echo $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['PHP_SELF']); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Rotterdam drempelvrij</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<?php echo $page['head']; ?>
</head>
<body>

<?php echo $page['contents'] . "\n"; ?>

</body>
</html>