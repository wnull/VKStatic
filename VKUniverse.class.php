<?php

/**
 * @author Vasily Pirajog
 * @contacts vk.com/id19933, t.me/pirajog, github.com/wnull
 */

class VKUniverse {

 private $vk_version = '5.71';
 private $massiv = [];

 public $likes = 0, $comments = 0, $views = 0, $reposts = 0, $attachments = 0;

 public function count_wall ($user_id, $filter, $access_token) {

  $page = 0;
  $count = 100;

  do {

   $offset = $page * $count;
  
   $scan = $this->api('wall.get', [
    'owner_id' => $user_id,
    'offset' => $offset,
    'count' => $count,
    'filter' => $filter,
    'v' => $this->vk_version,
    'access_token' => $access_token
   ]);

   if (isset($scan->error)) {

    exit(json_encode(['error' => ['error_code' => $scan->error->error_code, 'error_msg' => $scan->error->error_msg]]));

   }

   foreach ($scan->response->items as $key => $val) {
     
    $this->likes += $val->likes->count;
    $this->comments += $val->comments->count;
    $this->reposts += $val->reposts->count;

    if (!empty($val->views)) $this->views += $val->views->count;
    if (!empty($val->attachments)) $this->attachments += count($val->attachments);

    if (!empty($val->attachments)) foreach ($val->attachments as $keys => $value) array_push($this->massiv, $value->type);

   }

   $page++;

  } while ($scan->response->count > $offset + $count);

  return json_encode([
   'response' => [
    'likes' => $this->likes, 
    'comments' => $this->comments, 
    'reposts' => $this->reposts, 
    'views' => $this->views, 
    'attachments' => [
     'count' => $this->attachments, 
     'type' => array_count_values($this->massiv)
    ]
   ]
  ]);

 }

 protected function api ($method, $param) {

  return json_decode(@file_get_contents('https://api.vk.com/method/'.$method.'?'.http_build_query($param)));

 }

}

?>
