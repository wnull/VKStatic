<?php

/**
 * @author Vasily Pirajog
 * @contacts vk.com/id19933, t.me/pirajog, github.com/wnull
 */

clear_tmp('/tmp/', 900); // directory with tmp | time: 900 sec = 15 min

function clear_tmp ($dir, $time_ex) {

 if ($open = opendir($dir)) {

  while(($file = readdir($open)) !== false) if ((time() - filectime($dir . $file)) > $time_ex) unlink($dir . $file);

  closedir($open);  

 }

}
