<?php

/**
 * @author   Vasily Pirajog  <wlinlkin@yandex.ru>
 * @version  4.0
 * @link     gihtub.com/wnull
 * @license  MIT
 */

class VKStatic
{

	protected $attachments_db = [];

	public $owner_id,
		   $filter,
		   $version,
		   $access_token;

	public $likes = 0,
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
			$this->version = (isset($data['v'])) ? $data['v'] : '5.73';

			if ($data['owner_id'])
			{
				$this->owner_id = $data['owner_id'];
			}
			else
			{
				$self = $this->query('users.get', []);

				$this->owner_id = $self->response[0]->id;
			}

			$this->filter = (isset($data['filter'])) ? $data['filter'] : 'all';
		}

		$checkout = $this->query('users.get', []);

		if (isset($checkout->error))
		{
			throw new \Exception($checkout->error->error_msg);
		}

	}


	public function wall_scan ()
	{
		do
		{
			$offset = $this->page * $this->count;

			$wall = $this->query('wall.get', [
				'owner_id' => $this->owner_id,
				'offset' => $offset,
				'count' => $this->count,
				'filter' => $this->filter,
				'v' => '5.73'
			]);

			if (isset($wall->error))
			{
				throw new \Exception($wall->error->error_msg);
			}

			foreach ($wall->response->items as $key => $val)
			{
				$this->likes += $val->likes->count;
				$this->comments += $val->comments->count;
				$this->reposts += $val->reposts->count;

				if (!empty($val->views))
				{
					$this->views += $val->views->count;
				}

				if (!empty($val->attachments))
				{
					$this->attachments += count($val->attachments);

					foreach ($val->attachments as $keys => $value)
					{
						array_push($this->attachments_db, $value->type);
					}
				}
			}

			$this->page++;

		}
		while ($wall->response->count > $offset + $this->count);
				
		$data = [
			'response' => [
				'likes' => $this->likes,
				'comments' => $this->comments,
				'reposts' => $this->reposts,
				'views' => $this->views,
				'attachments' => [
					'count' => $this->attachments,
					'type' => array_count_values($this->attachments_db)
				]
			]
		];

		return json_encode($data);
	}


	protected function query ($method, $params)
	{
		$params['v'] = $this->version;
		$params['access_token'] = $this->access_token;

		$data = $this->curl([
			'url' => 'https://api.vk.com/method/' . $method,
			'post/build' => $params
		]);

		return json_decode($data);
	}


	protected function curl ($data)
	{
		if (!empty($data['url']))
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $data['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			if (isset($data['post']) || isset($data['post/build']))
			{
				curl_setopt($ch, CURLOPT_POST, true);
				
				if (isset($data['post']))
				{
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data['post']);
				}
				else
				{
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data['post/build']));
				}
			}

			$body = curl_exec($ch);
			curl_close($ch);

			return $body;
		}
	}

}

?>
