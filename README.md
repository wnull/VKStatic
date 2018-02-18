
#   VK_Universe

Free library for scanning the wall VK

<img src="https://img.shields.io/badge/version-3.2-red.svg"> <img src="https://img.shields.io/badge/php-%3E5.4-blue.svg">  <img src="https://img.shields.io/badge/update-16.02.2018-4a76a8.svg">
## Settings and start
``` php
require 'VKUniverse.class.php';

try {

 $access_token = 'user_token'; 
 $user_id = 1; 

 $foot = (new VKUniverse)->count_wall($user_id, 'owner', $access_token); // all || owner [ posts ]
 var_dump(json_decode($foot));

} catch (Exception $e) {

 echo 'Error: '.$e->getMessage();

}
```
## The result of the script execution

The result will be given in json format. On the example of the statistics of the wall of Paul Durov
``` 
{
  "response": 
  {
    "update": "12:54",
    "likes": 20060612,
    "comments": 1790532,
    "reposts": 905430,
    "views": 94388476,
    "attachments": 
    {
      "count": 267,
      "type": 
      {
        "video": 4,
        "link": 29,
        "photo": 210,
        "doc": 10,
        "poll": 4,
        "photos_list": 1,
        "page": 4,
        "album": 1,
        "note": 3,
        "graffiti": 1
      }
    }
  }
}

```
## Last changes 
<b> from 16 Feb 2018</b> <small>upd 17:50 MSK</small>
* Added caching (15 min)
* Exceptions added
* Added a script to clean the directory with the cache (clean.php)

## License and Authorship

MIT license, all rights belong to the author of the code <a target="_blank" href="https://vk.com/wnull">Vasily Pirajog</a>

<h4>Allowed free use with copyright notice!</h4>
