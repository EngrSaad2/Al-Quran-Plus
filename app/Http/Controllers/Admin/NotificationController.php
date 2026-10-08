<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    protected NotificationRepositoryInterface $notificationRepo;
    protected FirebaseService $firebaseService;

    public function __construct(
        NotificationRepositoryInterface $notificationRepo,
        FirebaseService $firebaseService
    ) {
        $this->notificationRepo = $notificationRepo;
        $this->firebaseService = $firebaseService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'is_active']);
        $notifications = $this->notificationRepo->getAllPaginated($filters, 10);
        return view('admin.notifications.index', compact('notifications', 'filters'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'short_description_en' => 'required|string|max:500',
            'short_description_bn' => 'required|string|max:500',
            'content_en' => 'required|string',
            'content_bn' => 'required|string',
            'notification_type' => 'required|string',
            'action_type' => 'required|in:publish_now,schedule,draft',
            'publish_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:3072',
            'send_push_now' => 'nullable|boolean',
            'target_audience' => 'nullable|string|in:all_users,english,bangla',
        ]);

        $data = $request->except(['image', 'banner_image', 'action_type', 'send_push_now', 'target_audience']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('notifications', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('notifications/banners', 'public');
        }

        if ($request->action_type === 'publish_now') {
            $data['is_active'] = true;
            $data['publish_date'] = now();
        } elseif ($request->action_type === 'schedule') {
            $data['is_active'] = true;
            $data['publish_date'] = $request->publish_date ?? now();
        } else {
            $data['is_active'] = false;
            $data['publish_date'] = null;
        }

        $notification = $this->notificationRepo->create($data);

        // If user selected "Send Notification" immediately via Firebase
        if ($request->boolean('send_push_now') && $notification->is_active) {
            $targetTopic = $request->input('target_audience', 'all_users');
            $this->sendPushNotification($notification, $targetTopic);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification saved successfully!');
    }

    public function show(int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification) {
            abort(404);
        }
        return view('admin.notifications.show', compact('notification'));
    }

    public function edit(int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification) {
            abort(404);
        }
        return view('admin.notifications.edit', compact('notification'));
    }

    public function update(Request $request, int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification) {
            abort(404);
        }

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'short_description_en' => 'required|string|max:500',
            'short_description_bn' => 'required|string|max:500',
            'content_en' => 'required|string',
            'content_bn' => 'required|string',
            'notification_type' => 'required|string',
            'is_active' => 'required|boolean',
            'publish_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['image', 'banner_image']);

        if ($request->hasFile('image')) {
            if ($notification->image) {
                Storage::disk('public')->delete($notification->image);
            }
            $data['image'] = $request->file('image')->store('notifications', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($notification->banner_image) {
                Storage::disk('public')->delete($notification->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('notifications/banners', 'public');
        }

        $this->notificationRepo->update($id, $data);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification updated successfully!');
    }

    public function destroy(int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if ($notification) {
            if ($notification->image) Storage::disk('public')->delete($notification->image);
            if ($notification->banner_image) Storage::disk('public')->delete($notification->banner_image);
            $this->notificationRepo->delete($id);
        }
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully!');
    }

    public function duplicate(int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification) {
            abort(404);
        }

        $newNotification = $notification->replicate();
        $newNotification->title_en = $notification->title_en . ' (Copy)';
        $newNotification->title_bn = $notification->title_bn . ' (কপি)';
        $newNotification->is_active = false;
        $newNotification->created_at = now();
        $newNotification->updated_at = now();
        $newNotification->save();

        return redirect()->route('admin.notifications.edit', $newNotification->id)
            ->with('success', 'Notification duplicated successfully. Edit draft copy below.');
    }

    public function sendPush(Request $request, int $id)
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
            }
            return back()->with('error', 'Notification not found.');
        }

        $target = $request->input('target_audience', 'all_users');
        $res = $this->sendPushNotification($notification, $target);
        $sentCount = $notification->logs()->where('status', 'sent')->count();
        $lastLog = $notification->logs()->where('status', 'sent')->latest('sent_at')->first();

        if ($res['success']) {
            $msg = 'FCM Push notification sent successfully to target: ' . $target;
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'sent_count' => $sentCount,
                    'last_sent_at' => $lastLog ? $lastLog->sent_at->timestamp : time(),
                    'id' => $id,
                ]);
            }
            return back()->with('success', $msg);
        }

        $err = 'FCM Push notification error: ' . ($res['error'] ?? 'Unknown error');
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $err], 400);
        }
        return back()->with('error', $err);
    }

    private function sendPushNotification(Notification $notification, string $target): array
    {
        $title = $notification->title_en;
        $body = $notification->short_description_en;

        $extraData = [
            'notification_id' => (string)$notification->id,
            'title_en' => $notification->title_en,
            'title_bn' => $notification->title_bn,
            'short_description_en' => $notification->short_description_en,
            'short_description_bn' => $notification->short_description_bn,
            'content_en' => $notification->content_en,
            'content_bn' => $notification->content_bn,
            'image' => $notification->image_url ?? asset('favicon.png'),
            'banner_image' => $notification->banner_image_url ?? $notification->image_url ?? asset('favicon.png'),
            'type' => $notification->notification_type,
            'publish_date' => $notification->publish_date ? $notification->publish_date->toIso8601String() : now()->toIso8601String(),
        ];

        $res = $this->firebaseService->sendPush($target, $title, $body, $extraData);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'target_type' => $target,
            'status' => $res['success'] ? 'sent' : 'failed',
            'sent_at' => now(),
            'error_message' => $res['error'] ?? null,
        ]);

        return $res;
    }
}
