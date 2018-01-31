<?php

/**
 * @author Vasily Pirajog
 * @contacts vk.com/id19933, t.me/pirajog, github.com/wnull
 */

class VK_Universe {

 const VERSION_VK = '5.71';

 public $likes, $comments, $views, $reposts;

 public function count_wall ($user_id, $filter, $access_token) {

  // $filt = ($filter) ? $filter : 'all';

  $page = 0;
  $count = 100;

  do {

   $offset = $page * $count;
  
   $scan = $this->api('wall.get', [
   	'owner_id' => $user_id,
   	'offset' => $offset,
   	'count' => $count,
   	'filter' => $filter,
   	'v' => self::VERSION_VK,
   	'access_token' => $access_token
   ]);

   foreach ($scan->response->items as $val) {

    $likes += $val->likes->count;
    $comments += $val->comments->count;
    $reposts += $val->reposts->count;
    $views += $val->views->count;
    $attachments += count($val->attachments);

   }

   $page++;

  } while ($scan->response->count > $offset + $count);

  return json_encode(['likes' => $likes, 'comments' => $comments, 'reposts' => $reposts,'views' => $view, 'attachments' => $attachments]);

 }

 protected function api ($method, $param) {

  return json_decode(@file_get_contents('https://api.vk.com/method/'.$method.'?'.http_build_query($param)));

 }

}

?>
