<?php

namespace App\Api\Repositories;

use App\Api\Snapshots\ItemSnapshot;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Репозиторий для сущности «продукт».
 */
class ItemRepository
{
    /** @var Builder */
    private $item;

    /** @var int */
    private $perPage;

    /** @var array */
    private $columns = ['*'];

    /**
     * Инициализация репозитория.
     */
    public function __construct()
    {
        $this->item = Item::query();
    }

    /**
     * @param int $perPage
     * @return ItemRepository
     */
    public function paginate(?int $perPage = 20): ItemRepository
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @param array $columns
     * @return ItemRepository
     */
    public function columns(array $columns): ItemRepository
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param ItemSnapshot $snapshot
     * @return Item|Model
     */
    public function create(ItemSnapshot $snapshot): Item
    {
        return $this->item->create([
            'id' => $snapshot->getId(),
            'title' => $snapshot->getTitle(),
            'protein' => $snapshot->getProtein(),
            'fat' => $snapshot->getFat(),
            'carbohydrates' => $snapshot->getCarbohydrates(),
            'fiber' => $snapshot->getFiber(),
            'user_id' => $snapshot->getUserId(),
        ]);
    }

    /**
     * @param string $id
     * @return Item|Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(string $id): Item
    {
        return $this->item->findOrFail($id);
    }

    /**
     * @param string $id
     * @return array
     */
    public function findByOwner(string $id): array
    {
        $query = $this->item->where('user_id', $id);

        if ($this->perPage !== null) {
            $query->paginate($this->perPage);
        }

        return $query->get($this->columns);
    }
}