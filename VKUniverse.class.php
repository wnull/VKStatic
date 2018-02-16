<?php

/**
 * @author Vasily Pirajog
 * @contacts vk.com/id19933, t.me/pirajog, github.com/wnull
 */

class VKUniverse {

 private $link = 'https://api.vk.com/method/';
 private $vk_version = '5.71';
 private $array = [];

 public $likes = 0, $comments = 0, $views = 0, $reposts = 0, $attachments = 0, $count = 100, $page = 0;

 public function count_wall ($user_id, $filter, $access_token) {

  $file_cache = 'tmp/'.$user_id.'.json';
    
  if (file_exists($file_cache) && filemtime($file_cache) > time() - 900) {

   $data = file_get_contents($file_cache);

  } else {
 
   do { 

    $offset = $this->page * $this->count;
   
    $scan = $this->api('wall.get', [
     'owner_id' => $user_id,
     'offset' => $offset,
     'count' => $this->count,
     'filter' => $filter,
     'v' => $this->vk_version,
     'access_token' => $access_token
    ]); 
 

    if (isset($scan->error)) {
     throw new Exception($scan->error->error_msg);
    } 

    foreach ($scan->response->items as $key => $val) {
      
     $this->likes += $val->likes->count;
     $this->comments += $val->comments->count;
     $this->reposts += $val->reposts->count; 

     if (!empty($val->views)) {
      $this->views += $val->views->count;
     } 

     if (!empty($val->attachments)) {
      $this->attachments += count($val->attachments);
     } 

     if (!empty($val->attachments)) {
      foreach ($val->attachments as $keys => $value) array_push($this->array, $value->type);
     } 

    } 

    $this->page++; 

   } while ($scan->response->count > $offset + $this->count); 

   date_default_timezone_set('Europe/Moscow');

   $data = json_encode([
    'response' => [
     'update' => date('H:i'),
     'likes' => $this->likes, 
     'comments' => $this->comments, 
     'reposts' => $this->reposts, 
     'views' => $this->views, 
     'attachments' => [
      'count' => $this->attachments, 
      'type' => array_count_values($this->array)
     ]
    ]
   ], JSON_UNESCAPED_UNICODE);

   file_put_contents($file_cache, $data, LOCK_EX);

  }

  return $data;

 }

 protected function api ($method, $param) {
  return json_decode($this->curl($this->link.'/'.$method, ['post' => $param]));
 }

 protected function curl ($url, $params = null) { 

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url); 

  if (isset($params['post'])) {
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['post'])); 
  }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

  if (isset($params['header'])) {
   curl_setopt($ch, CURLOPT_HTTPHEADER, $params['header']);
  }

  $content = curl_exec($ch);
  curl_close ($ch);
  
  return $content;

 }

}