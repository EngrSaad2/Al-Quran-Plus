<?php

namespace App\Repositories\Contracts;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getAllPaginated(array $filters, int $perPage = 15): LengthAwarePaginator;
    public function getById(int $id): ?Notification;
    public function create(array $data): Notification;
    public function update(int $id, array $data): ?Notification;
    public function delete(int $id): bool;
    public function getActiveForApi(string $language = 'en', int $perPage = 20): LengthAwarePaginator;
}
