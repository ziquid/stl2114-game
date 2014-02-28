<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language ?>" xml:lang="<?php print $language->language ?>">
<head>
  <?php print $head ?>
  <title><?php print $head_title ?></title>
  <?php print $styles ?>
  <?php print $scripts ?>
  <meta name="format-detection" content="telephone=no" />
  <meta name="format-detection" content="email=no" />
  <meta name="format-detection" content="address=no" />
</head>

<body <?php print theme("onload_attribute"); ?>>
<?php print $content ?>
<div id="footer">
  <?php print $footer_message ?>
  <?php print $footer ?>
</div>
<?php print $closure ?>
<div id="copyright">
  Copyright &copy; 2013-2014 Ziquid Design Studio.&nbsp; All Rights Reserved.
<br/><br/>&nbsp;
</div>
</body>
</html>
