<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Notification\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return response()->json($notifications);
    }

    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function getPreferences(): JsonResponse
    {
        $prefs = NotificationPreference::where('user_id', Auth::id())->get();
        return response()->json($prefs);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.*.type' => 'required|string',
            'preferences.*.channels' => 'required|array'
        ]);

        foreach ($validated['preferences'] as $prefData) {
            NotificationPreference::updateOrCreate(
                ['user_id' => Auth::id(), 'notification_type' => $prefData['type']],
                ['channels' => $prefData['channels']]
            );
        }
        return response()->json(['message' => 'Préférences mises à jour.']);
    }
}
