<html lang="ru">

<head>
  <title>
    QTT
    <?php echo $data->user; ?>
  </title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="<?php $url->assets('css.mincss.siteStyle-min.css'); ?>">
  <link href="https://fonts.googleapis.com/css?family=PT+Sans&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

</head>

<body>

  <?php include $url->incView($contentView); ?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    window.jQuery || document.write('<script src="/../assets/js/vendor/jquery-3.2.1.min.js"><\/script>')
  </script>
  <link rel="stylesheet" type="text/css" href="$url->assets('css.mincss.siteStyle-min.css'); ">
  <script src="<?php $url->assets('js.min.firstPageScripts-min.css'); ?>"></script>
  <!-- <script src = "$curPath/assets/js/min/firstPageScripts.js"></script> -->
</body>

</html>
