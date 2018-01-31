# VK_Universe
Class for VK for wall scanning
## Settings and start
``` php
include 'VKUniverse.class.php';

$access_token = 'user_token'; // access_token user
$user_id = 1; // id wall

$foot = (new VKUniverse)->count_wall($user_id, 'owner', $access_token); // all || owner [ posts ]

var_dump($foot); // result of dump json
```
