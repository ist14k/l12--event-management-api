<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relations = request()->query('include');
        $eventsQuery = Event::query();

        if ($relations) {
            $validRelations = $this->includeRelations($relations);
            if ($validRelations) {
                $eventsQuery->with($validRelations);
            }
        }

        $events = $eventsQuery->paginate(10);

        return EventResource::collection($events);
    }

    // TODO: Move this to a dedicated service or helper class [trait] if it grows more complex
    private function includeRelations(string $relations): array|null
    {
        $allowedRelations = ['user', 'attendees', 'attendees.user'];
        $requestedRelations = explode(',', $relations);
        $validRelations = array_intersect($requestedRelations, $allowedRelations);

        return !empty($validRelations) ? $validRelations : null;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $event = Event::create(array_merge($validated, ['user_id' => $request->user()->id]));

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($event->load(['user', 'attendees']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
        ]);

        $event->update($validated);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ], 204);
    }
}
