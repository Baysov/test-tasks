<?php

  header("Cache-Control: no-store, no-cache,  must-revalidate"); 
  header("Expires: " .  date("r"));
  header("Content-type: text/html; charset=utf-8");
  header( 'HTTP/1.1 404 Not Found' );
 
?>

<!DOCTYPE html>
<html>
<head>
<title>Ошибка 404</title>
<meta name="robots" content="noindex,nofollow" />
<META NAME="keywords" CONTENT="ошибка 404">
</head>

<body>
<div align="center" style="font-size:20px;">
 Запрашиваемая страница не найдена<br>
 <a href="<?php print config::full_host_name(); ?>" title="главная" onFocus="this.blur();" rel="nofollow" target="_self" id="lws_but">Перейти на <?php print config::host_name(); ?></a>
</div>

<script type="text/javascript">
 setTimeout(function(){ location.href = '<?php print config::full_host_name(); ?>'; },1000);
</script>

</body>
</html>
