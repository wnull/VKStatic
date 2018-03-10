<?php

/**
 * Class for scanning the wall in VKontakte
 *
 * @author     Vasily Pirajog <wlinkin@yandex.ru> | github.com/wnull
 * @copyright  2017-2018
 * @license    MIT
 * @version    Release: 3.1
 * @link       github.com/wnull/VK_Universe
 * @since      Class available since Release 2.5
 *
 */

class VK_Universe
{
	public	$link = 'https://api.vk.com/method/';
	public	$vk_version = '5.71';
	public	$type_array = [];

	public	$filter = 'all';
	public	$tmp = 'tmp';
	public	$owner_id = 0;
	public	$access_token;
	public	$time_cash = 900;

	public	$likes = 0,
		$comments = 0,
		$views = 0,
		$reposts = 0,
		$attachments = 0,
		$count = 100,
		$page = 0;

	public function __construct ($data)
	{
		if (!empty($data))
		{
			$this->access_token = ($data['access_token']) ? $data['access_token'] : '';
			$this->time_cash = ($data['time_cash']) ? $data['time_cash'] : $this->time_cash;
			$this->tmp = ($data['tmp']) ? $data['tmp'] : $this->tmp;

			if ($data['owner_id'])
			{
				$this->owner_id = $data['owner_id'];
			}
			else
			{
				$self = $this->vk_api('users.get', [
					'access_token' => $this->access_token,
					'v' => $this->vk_version
				]);

				$this->owner_id = $self->response[0]->id;
			}

			$this->filter = $data['filter'] ? ($data['filter']) : $this->filter;
		}

		$checkout = $this->vk_api('users.get', [
			'access_token' => $this->access_token,
			'v' => $this->vk_version
		]);

		if ($checkout->error)
		{
			throw new Exception($checkout->error->error_msg);
		}
	}

	public function stats ()
	{
		$file_cache = $this->tmp.'/'.$this->owner_id.'.json';

		if (file_exists($file_cache) && filemtime($file_cache) > time() - $this->time_cash)
		{
			$data = file_get_contents($file_cache);
		}
		else
		{
			do
			{
				$offset = $this->page * $this->count;

				$wall = $this->vk_api('wall.get', [
					'owner_id' => $owner_id,
					'offset' => $offset,
					'count' => $this->count,
					'filter' => $filter,
					'v' => $this->vk_version,
					'access_token' => $this->access_token
				]);

				if (isset($wall->error))
				{
					throw new Exception($wall->error->error_msg);
				}

				foreach ($wall->response->items as $key => $val)
				{
					$this->likes += $val->likes->count;
					$this->comments += $val->comments->count;
					$this->reposts += $val->reposts->count;

					if (!empty($val->views) || !empty($val->attachments))
					{
						$this->views += $val->views->count;
						$this->attachments += count($val->attachments);

						foreach ($val->attachments as $keys => $value)
						{
							array_push($this->type_array, $value->type);
						}
					}
				}

				$this->page++;

			}
			while ($wall->response->count > $offset + $this->count);
				
			$data = $this->build_view([
				'response' => [
					'likes' => $this->likes,
					'comments' => $this->comments,
					'reposts' => $this->reposts,
					'views' => $this->views,
					'attachments' => [
						'count' => $this->attachments,
						'type' => array_count_values($this->type_array)
					]
				]
			]);

			file_put_contents($file_cache, $data, LOCK_EX);
		}

		return $data;
	}


	protected function vk_api ($method, $params)
	{
		$tr = $this->curl($this->link.'/'.$method, [
			'post/build' => $params
		]);

		return ($tr) ? json_decode($tr) : false;
	}


	protected function build_view ($data)
	{
		return ($data) ? json_encode($data) : false;
	}


	protected function curl ($url, $params)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		if (isset($params['post/build']))
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['post/build']));
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if (isset($params['headers']))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);
		}

		$con = curl_exec($ch);

		curl_close($ch);

		return $con;
	}

}

?>
