<?php

namespace App\Events;

use App\Models\Result;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FilterMediaEvent
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $result;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */

	public function __construct($path, $type, $status, $reason = '')
	{
		@unlink($path);

		$this->result = Result::create([
			'type'			=> $type,
			'status'			=> $status,
			'reason'			=> $reason,
		]);
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('channel-name');
	}
}
