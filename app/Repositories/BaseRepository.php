<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IBaseRepository;

class BaseRepository implements IBaseRepository
{
    public $model;

    /**
     * BaseRepository constructor
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Gel all models
     * 
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model
            ->with($relations)
            ->get($columns);
    }

    /**
     * Get all trashed models
     * 
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model
            ->onlyTrashed()
            ->get();
    }

    /**
     * Find model by id
     * 
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Model
     */
    public function findById(
        int $modelId, 
        array $columns = ['*'], 
        array $relations = []
    ):? Model 
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->find($modelId);
    }

    /**
     * Find items by column name
     * 
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function findItemsByColumnName(
        string $columnName,
        $columnValue,
        array $columns = ['*'], 
        array $relations = []
    ):? Collection
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->where($columnName, $columnValue)
            ->get();
    }

    /**
     * Find trashed model by id.
     * 
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId):? Model
    {
        return $this->model
            ->withTrashed()
            ->find($modelId);
    }

    /**
     * Find only trashed model by id.
     * 
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId):? Model
    {
        return $this->model
            ->onlyTrashed()
            ->find($modelId);
    }

    /**
     * Store data
     * 
     * @param array $payload
     * @return Model
     */
    public function create(array $payload):? Model
    {
        $model = $this->model;

        return $model->create($payload);
    }
    
    /**
     * Update or Store data
     * 
     * @param array $conditionData
     * @param array $payload
     * @return Model
     */
    public function updateOrCreate(array $conditionData = null, array $payload):? Model
    {
        $model = $this->model;
        return $model->updateOrCreate(
            $conditionData,
            $payload
        );;
    }

    /** 
     * Update existing model
     * 
     * @param int $modelId
     * @param array $payload
     * @return Model
     */
    public function update(int $modelId, array $payload):? Model
    {
        $model = $this->model;
        $primaryKey = $model->getKeyName();
        $model->where($primaryKey, $modelId)->update($payload);
        
        return $model;
    }
    
    /**
     * Delete data by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        $model = $this->model;
        $primaryKey = $model->getKeyName();

        return $model
            ->where($primaryKey, $modelId)
            ->delete();
    }

    /**
     * Restore data by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function restoreById(int $modelId):? Model
    {
        return $this->findOnlyTrashedById($modelId)
                ->restore();
    }

    /**
     * Permanently deleted model by id.
     * 
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)
                ->forceDelete();
    }
}