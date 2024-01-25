<?php

namespace App\Repositories;

use App\Models\Hook\HookModel;
use Illuminate\Support\Collection;

class HookRepository implements HookRepositoryInterface
{
    /**
     * @param array $data
     *
     * @return HookModel
     */
    public function store(array $data): HookModel
    {
        return HookModel::updateOrCreate($data);
    }

    /**
     * @param string $hash
     *
     * @return Collection
     */
    public function findAllBySha(string $hash): Collection
    {
        return HookModel::where('hash', '=', $hash)
            ->get()
        ;
    }

    /**
     * @param string $event
     * @param string $hash
     *
     * @return null|HookModel
     */
    public function findOneByEventSha(string $event, string $hash): ?HookModel
    {
        return HookModel::where('event', '=', $event)
            ->where('hash', '=', $hash)
            ->orderByDesc('id')
            ->first()
        ;
    }

    /**
     * @param string $event
     * @param string $hash
     *
     * @return Collection
     */
    public function findAllByEventSha(string $event, string $hash): Collection
    {
        return HookModel::where('event', '=', $event)
            ->where('hash', '=', $hash)
            ->orderBy('id')
            ->get()
        ;
    }
}
