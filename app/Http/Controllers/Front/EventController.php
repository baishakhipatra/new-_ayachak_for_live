<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    //

    public function index(Request $request)
    {
        $query = Event::query()->with('eventImage')->orderBy('start_time', 'desc')->where('status',1);

        if ($request->filled('term')) {
            $search = $request->term;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
        }

        $events = $query->paginate(6); // paginate for frontend

        return view('front.events.index', compact('events'));
    }

    public function details($slug){
        $event = Event::with(['eventImage', 'relatedEventDetails.eventImage'])
            ->where('slug', $slug)
            ->first();

            if (!$event) {
                dd('Event not found for slug: ' . $slug);
            }

        return view('front.events.detail', compact('event'));
    }
}
