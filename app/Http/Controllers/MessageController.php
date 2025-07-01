<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display messages for a specific order
     */
    public function index(Order $order)
    {
        // Check if user is authorized to view messages for this order
        if ($order->client_id !== Auth::id() && $order->provider_id !== Auth::id()) {
            abort(403, 'You can only view messages for your own orders.');
        }

        $messages = Message::where('order_id', $order->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read for the current user
        $messages->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->each(function ($message) {
                $message->markAsRead();
            });

        return view('messages.index', compact('order', 'messages'));
    }

    /**
     * Store a new message
     */
    public function store(Request $request, Order $order)
    {
        // Check if user is authorized to send messages for this order
        if ($order->client_id !== Auth::id() && $order->provider_id !== Auth::id()) {
            abort(403, 'You can only send messages for your own orders.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'message_type' => 'nullable|string|in:text,file,image',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $messageData = [
            'order_id' => $order->id,
            'sender_id' => Auth::id(),
            'receiver_id' => Auth::id() === $order->client_id ? $order->provider_id : $order->client_id,
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
        ];

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('messages', $fileName, 'public');
            $messageData['file_path'] = $filePath;
            $messageData['message_type'] = $file->getClientMimeType();
        }

        $message = Message::create($messageData);

        // TODO: Send notification to receiver
        // $message->receiver->notify(new NewMessageNotification($message));

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(Message $message)
    {
        // Check if user is the receiver of this message
        if ($message->receiver_id !== Auth::id()) {
            abort(403, 'You can only mark your own messages as read.');
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all messages in an order as read
     */
    public function markAllAsRead(Order $order)
    {
        // Check if user is authorized
        if ($order->client_id !== Auth::id() && $order->provider_id !== Auth::id()) {
            abort(403, 'You can only mark messages as read for your own orders.');
        }

        Message::where('order_id', $order->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count for current user
     */
    public function getUnreadCount()
    {
        $unreadCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Download a message attachment
     */
    public function downloadAttachment(Message $message)
    {
        // Check if user is authorized to download this attachment
        if ($message->order->client_id !== Auth::id() && $message->order->provider_id !== Auth::id()) {
            abort(403, 'You can only download attachments for your own orders.');
        }

        if (!$message->hasAttachment()) {
            abort(404, 'No attachment found.');
        }

        $filePath = storage_path('app/public/' . $message->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }
}
