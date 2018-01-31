<?php

include 'VKUniverse.class.php';

$access_token = 'user_token';
$user_id = 1; // your id vk

$foot = (new VK_Universe)->count_wall($user_id, 'owner', $access_token)); // all || owner

var_dump($foot); // dump json

?>
