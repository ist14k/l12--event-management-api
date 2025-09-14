<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $events = \App\Models\Event::all();

        foreach ($events as $event) {
            // Get a random number of attendees for each event
            $attendeeCount = rand(5, 15);
            $attendees = $users->random($attendeeCount);

            foreach ($attendees as $attendee) {
                // Avoid duplicate entries
                if (!\App\Models\Attendee::where('user_id', $attendee->id)->where('event_id', $event->id)->exists()) {
                    \App\Models\Attendee::create([
                        'user_id' => $attendee->id,
                        'event_id' => $event->id,
                    ]);
                }
            }
        }
    }
}
