<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = Auth::user();
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];

        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true]);
    }
    
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required'
        ]);

        $user = Auth::user();
        $user->deletePushSubscription($request->endpoint);

        return response()->json(['success' => true]);
    }
}
