<!DOCTYPE html>
<?php header_remove('X-Frame-Options'); // See https://www.drupal.org/node/2735873 ?>
<head>
<title><?php print $head_title; ?></title>
<?php print $styles; ?>
</head>

<body style="background-color: #FBFBF9;">
<?php print $page; ?>
</body>
</html>
