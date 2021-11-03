<?php
$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
$linkForder = $_SERVER['DOCUMENT_ROOT'].'/storage';

echo($targetFolder. "<br>");
echo($linkForder);
//symlink($targetFolder,$linkForder);
//echo 'success';
?>