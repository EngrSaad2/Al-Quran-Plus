<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    protected NotificationRepositoryInterface $notificationRepo;

    public function __construct(NotificationRepositoryInterface $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * GET /api/notifications
     * Supports language filter (lang=en|bn), per_page, page.
     */
    public function index(Request $request): JsonResponse
    {
        $lang = $request->query('lang', 'en');
        $perPage = (int)$request->query('per_page', 20);

        $paginator = $this->notificationRepo->getActiveForApi($lang, $perPage);

        $transformedItems = collect($paginator->items())->map(function ($item) use ($lang) {
            return [
                'id' => $item->id,
                'title_en' => $item->title_en,
                'title_bn' => $item->title_bn,
                'title' => $lang === 'bn' ? $item->title_bn : $item->title_en,
                'short_description_en' => $item->short_description_en,
                'short_description_bn' => $item->short_description_bn,
                'short_description' => $lang === 'bn' ? $item->short_description_bn : $item->short_description_en,
                'content_en' => $item->content_en,
                'content_bn' => $item->content_bn,
                'content' => $lang === 'bn' ? $item->content_bn : $item->content_en,
                'image' => $item->image_url,
                'banner_image' => $item->banner_image_url,
                'notification_type' => $item->notification_type,
                'publish_date' => $item->publish_date ? $item->publish_date->toIso8601String() : $item->created_at->toIso8601String(),
                'created_at' => $item->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $transformedItems,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more' => $paginator->hasMorePages(),
            ],
        ]);
    }

    /**
     * GET /api/notifications/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $notification = $this->notificationRepo->getById($id);
        if (!$notification || !$notification->is_active) {
            return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
        }

        $lang = $request->query('lang', 'en');

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $notification->id,
                'title_en' => $notification->title_en,
                'title_bn' => $notification->title_bn,
                'title' => $lang === 'bn' ? $notification->title_bn : $notification->title_en,
                'short_description_en' => $notification->short_description_en,
                'short_description_bn' => $notification->short_description_bn,
                'short_description' => $lang === 'bn' ? $notification->short_description_bn : $notification->short_description_en,
                'content_en' => $notification->content_en,
                'content_bn' => $notification->content_bn,
                'content' => $lang === 'bn' ? $notification->content_bn : $notification->content_en,
                'image' => $notification->image_url,
                'banner_image' => $notification->banner_image_url,
                'notification_type' => $notification->notification_type,
                'publish_date' => $notification->publish_date ? $notification->publish_date->toIso8601String() : $notification->created_at->toIso8601String(),
                'created_at' => $notification->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * POST /api/device-token
     */
    public function storeDeviceToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'language' => 'nullable|string',
            'platform' => 'nullable|string',
            'device_name' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $token = DeviceToken::updateOrCreate(
            ['fcm_token' => $validated['fcm_token']],
            [
                'language' => $validated['language'] ?? 'en',
                'platform' => $validated['platform'] ?? 'android',
                'device_name' => $validated['device_name'] ?? null,
                'app_version' => $validated['app_version'] ?? '1.0',
                'last_seen' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Device token registered successfully',
            'data' => $token,
        ]);
    }

    /**
     * POST /api/token/update
     */
    public function updateDeviceToken(Request $request): JsonResponse
    {
        return $this->storeDeviceToken($request);
    }

    /**
     * GET /api/app/version
     */
    public function getAppVersion(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'latest_version' => '1.0.0',
                'min_supported_version' => '1.0.0',
                'force_update' => false,
                'update_url' => 'https://quran.triangletech.com.bd',
                'release_notes' => 'Notification System and Performance Enhancements',
            ],
        ]);
    }
}
