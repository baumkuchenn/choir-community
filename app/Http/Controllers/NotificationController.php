<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect to the stored route or URL
        $url = $notification->data['url'] ?? route('management.index');

        return redirect($url);
    }
}
