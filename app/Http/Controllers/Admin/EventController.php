<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Event, EventImage, RelatedEvent};

class EventController extends Controller
{
    //

    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('term')) {
            $search = $request->term;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->with('eventImage')->orderBy('id', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $allEvents = Event::all();
        $relatedEvents = $allEvents->pluck('title', 'id')->toArray();
        return view('admin.events.create', compact('relatedEvents'));
    }

    public function store(Request $request) {
        // dd($request->all());
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required',
            'venue'         => 'required|string|max:255',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after_or_equal:start_time',
            'has_registration'  => 'nullable|boolean',
            'related_events'    => 'nullable|array',
            'related_events.*'  => 'exists:events,id',
            'event_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['slug'] = \Str::slug($data['title']);
        // $data['status'] = $data['status'] ?? 1;

        $event = Event::create($data);

        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            if ($image->isValid()) {
                $fileName = time() . rand(10000, 99999) . '.' . $image->extension();
                $filePath = 'uploads/events/' . $fileName;
                $image->move(public_path('uploads/events'), $fileName);

                EventImage::create([
                    'event_id'   => $event->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        if(!empty($data['related_events'])) {
            foreach($data['related_events'] as $relatedEventId) {
                RelatedEvent::create([
                    'event_id'  => $event->id,
                    'related_event_id'  => $relatedEventId,
                ]);
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully');
    }

    public function status(Request $request, $id)
    {
        $data = Event::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }

    public function edit($id) {
        $event = Event::with(['eventImage', 'relatedEvents'])->findOrFail($id);

        $allEvents = Event::where('id', '!=', $id)->get();
        $relatedEvents = $allEvents->pluck('title', 'id')->toArray();

        $selectedRelatedEvents = $event->relatedEvents->pluck('related_event_id')->toArray();
        return view('admin.events.edit', compact('event', 'allEvents', 'relatedEvents', 'selectedRelatedEvents'));
    }

    public function update(Request $request)
    {
        $event = Event::findOrFail($request->id);

        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required',
            'venue'             => 'required|string|max:255',
            'start_time'        => 'required|date',
            'end_time'          => 'required|date|after_or_equal:start_time',
            'has_registration'  => 'nullable|boolean',
            'related_events'    => 'nullable|array',
            'related_events.*'  => 'exists:events,id',
            'event_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['slug'] = \Str::slug($data['title']);

        // Update event main data
        $event->update($data);

        // Handle event image update
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            if ($image->isValid()) {
                $fileName = time() . rand(10000, 99999) . '.' . $image->extension();
                $filePath = 'uploads/events/' . $fileName;
                $image->move(public_path('uploads/events'), $fileName);

                // Delete old image file if exists
                if ($event->eventImage && file_exists(public_path($event->eventImage->image_path))) {
                    @unlink(public_path($event->eventImage->image_path));
                }

                // Update or create new image record
                EventImage::updateOrCreate(
                    ['event_id' => $event->id],
                    ['image_path' => $filePath]
                );
            }
        }

        // Handle related events update
        RelatedEvent::where('event_id', $event->id)->delete(); // remove old
        if (!empty($data['related_events'])) {
            foreach ($data['related_events'] as $relatedEventId) {
                RelatedEvent::create([
                    'event_id'          => $event->id,
                    'related_event_id'  => $relatedEventId,
                ]);
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }

    public function delete(Request $request) {
        // Get event data by ID
        $event = Event::findOrFail($request->id);
        // If event is not found then return status 404 with error message
        if (!$event) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Event is not found',
            ]);
        }
        $imagePath = $event->image;
        // Delete event from db
        $event->delete();
        // If file is exist ithen remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Event has been deleted successfully',
        ]);
    }

}
