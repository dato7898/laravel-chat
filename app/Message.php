<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\MessageSendEvent;

class Message extends Model
{
    /**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = ['message', 'receiver_id'];
	
	/**
	 * A message belong to a user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
	  return $this->belongsTo(User::class);
	}
	
}
