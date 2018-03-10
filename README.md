
#   VK_Universe

Free library for scanning the wall VK

<img src="https://img.shields.io/badge/version-3.2-red.svg"> <img src="https://img.shields.io/badge/php-%3E5.4-blue.svg">  <img src="https://img.shields.io/badge/update-10 Mar 2018-4a76a8.svg">
## Settings and start
``` php
require 'VKUniverse.class.php';

try 
{
	$data['access_token'] = '';
	$data['owner_id'] = '';
	$data['filter'] = 'all';
	$data['tmp'] = '';
	$data['time_cash'] = 900;

	$vk = (new VK_Universe($data))->stats();

	echo $vk;
}
catch (Exception $e)
{
	exit($e->getMessage());
}

```
## Settings

* access_token - required parameter
* ownder_id - optional parameter, if not specify, the token id will be used
* filter - optional parameter, default - all (allowable values ALL or OWNER ])
* tmp - optional parameter, cache write directory, default directory TMP 
* time_cash - optional parameter, cache storage time [seconds], default 900 ( 5 min )

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
## License and Authorship

MIT license, all rights belong to the author of the code <a target="_blank" href="https://vk.com/wnull">Vasily Pirajog</a> or e-mail: wlinkin@yandex.ru

<h4>Allowed free use with copyright notice!</h4>
