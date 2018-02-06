# VK_Universe 
* Version: 3
* Last update: 06 Feb 2018
## Settings and start
``` php
require 'VKUniverse.class.php';

$access_token = 'user_token'; // access_token user
$user_id = 1; // id wall

$foot = (new VKUniverse)->count_wall($user_id, 'owner', $access_token); // all || owner [ posts ]
echo $foot;

```
## License and Authorship

MIT license, all rights belong to the author of the code <a target="_blank" href="https://vk.com/wnull">Vasily Pirajog</a>

<h4>Allowed free use with copyright notice!</h4>
