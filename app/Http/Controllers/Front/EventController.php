<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
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
        $event = Event::with(['eventImage','relatedEventDetails' => function($q) {$q->where('status', 1)->with('eventImage');}])
            ->where('slug', $slug)
            ->where('status',1)
            ->first();

            if (!$event) {
                return view('front.404');
            }

        return view('front.events.detail', compact('event'));
    }
}
