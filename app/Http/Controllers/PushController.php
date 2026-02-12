<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required',
        ]);

        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];

        $user = $request->user();
        $user->updatePushSubscription($endpoint, $key, $token);
        $user->update(['push_enabled_at' => now()]);

        return response()->json(['success' => true], 200);
    }

    public function status(Request $request)
    {
        $user = $request->user();
        $hasSubscription = $user->pushSubscriptions()->exists();
        
        return response()->json([
            'subscribed' => $hasSubscription,
            'count' => $user->pushSubscriptions()->count()
        ]);
    }

    public function test(Request $request)
    {
        $user = $request->user();
        
        try {
            $user->notify(new \App\Notifications\TestPushNotification());
            return response()->json(['success' => true, 'message' => 'Notifikasi dikirim!'], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
