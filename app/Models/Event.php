<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use softDeletes;

    protected $table = "events";
    protected $fillable = ['title','slug', 'status', 'description', 'venue', 'start_time', 'end_time', 'has_registartion', 'deleted_at'];

    public function eventImage() {
        return $this->hasOne(EventImage::class,'event_id','id');
    }

    public function relatedEvents()
    {
        return $this->hasMany(RelatedEvent::class, 'event_id');
    }

    public function relatedEventDetails()
    {
        return $this->belongsToMany(
            Event::class,
            'related_events',
            'event_id',         // Foreign key in pivot pointing to this event
            'related_event_id'  // Foreign key pointing to the related event
        );
    }
}
