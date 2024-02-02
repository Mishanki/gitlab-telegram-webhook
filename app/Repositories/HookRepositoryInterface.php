<?php

namespace App\Repositories;

use App\Models\Hook\HookModel;
use Illuminate\Support\Collection;

interface HookRepositoryInterface
{
    public function store(array $data): HookModel;

    public function findAllBySha(string $hash): Collection;

    public function findOneByEventSha(string $event, string $hash, ?int $messageId = null): ?HookModel;

    public function findAllByEventSha(string $event, string $hash, ?int $messageId = null): Collection;
}
