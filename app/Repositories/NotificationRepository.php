<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getAllPaginated(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Notification::query()->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('short_description_en', 'like', "%{$search}%")
                  ->orWhere('short_description_bn', 'like', "%{$search}%");
            });
        }

        if (isset($filters['type']) && $filters['type'] !== 'all') {
            $query->where('notification_type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool)$filters['is_active']);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function update(int $id, array $data): ?Notification
    {
        $notification = $this->getById($id);
        if ($notification) {
            $notification->update($data);
        }
        return $notification;
    }

    public function delete(int $id): bool
    {
        $notification = $this->getById($id);
        if ($notification) {
            return $notification->delete();
        }
        return false;
    }

    public function getActiveForApi(string $language = 'en', int $perPage = 20): LengthAwarePaginator
    {
        $query = Notification::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            })
            ->latest('publish_date');

        return $query->paginate($perPage);
    }
}
