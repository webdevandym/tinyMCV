<?php

$path = 'app/Views/';
include $path.'templates/header.php';
include $path.'templates/navpanel.php';
include $path.$contentView;
include $path.'/templates/footer.php';
include 'app/Views/templates/connectjs.php';

?>

</body>
</html>
