<?php

include 'VKUniverse.class.php';

$access_token = 'user_token';

$foot = (new VK_Universe)->count_wall($user_id, 'owner', $access_token)); // all || owner

var_dump($foot); // dump json

?>
