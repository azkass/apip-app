<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function showForm()
    {
        return view('activity-form');
    }

    public function generateTable(Request $request)
    {
        $validated = $request->validate([
            'activities' => 'required|array',
            'durations' => 'required|array',
            'actor_roles' => 'required|array',
            'actors' => 'required|array',
        ]);

        $activities = $validated['activities'];
        $durations = $validated['durations'];
        $actorRoles = $validated['actor_roles'];
        $actors = $validated['actors'];

        return view('generate-table', compact('activities', 'durations', 'actorRoles', 'actors'));
    }
}
