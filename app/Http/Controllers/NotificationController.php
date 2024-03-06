<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markMessageAsRead(Request $request, $transactionId) {
        $user = Auth::user();
        $user->unreadNotifications()
            ->where(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewAppointmentMessage')
                    ->where('data->appointment_id', $transactionId);
            })
            ->orWhere(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewDocumentRequestMessage')
                    ->where('data->documentRequest_id', $transactionId);
            })
            ->orWhere(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewInquiryMessage')
                    ->where('data->inquiry_id', $transactionId);
            })
            ->update(['read_at' => now()]);
    
        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function markNotificationAsRead(Request $request, $transactionId) {
        $user = Auth::user();
        $user->unreadNotifications()
            ->where(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewAppointment')
                    ->where('data->appointment_id', $transactionId);
            })
            ->orWhere(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewDocumentRequest')
                    ->where('data->documentRequest_id', $transactionId);
            })
            ->orWhere(function ($query) use ($transactionId) {
                $query->where('type', 'App\Notifications\NewInquiry')
                    ->where('data->inquiry_id', $transactionId);
            })
            ->update(['read_at' => now()]);
    
        return response()->json(['message' => 'Notifications marked as read']);
    }
    
    public function markAllMessagesAsRead(Request $request) {
        $user = Auth::user();
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\NewAppointmentMessage')
            ->orWhere('type', 'App\Notifications\NewDocumentRequestMessage')
            ->orWhere('type', 'App\Notifications\NewInquiryMessage')
            ->update(['read_at' => now()]);
            
        return response()->json(['message' => 'All messages marked as read']);
    }

    public function markAllNotificationsAsRead(Request $request) {
        $user = Auth::user();
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\NewAppointment')
            ->orWhere('type', 'App\Notifications\NewDocumentRequest')
            ->orWhere('type', 'App\Notifications\NewInquiry')
            ->update(['read_at' => now()]);
            
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function showAllMessages() {
        return view('messages');
    }

    public function showAllNotifications() {
        return view('notifications');
    }
}
