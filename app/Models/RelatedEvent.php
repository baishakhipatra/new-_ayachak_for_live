<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelatedEvent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "related_events";
    protected $fillable = ['event_id', 'related_event_id', 'deleted_at'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

}
