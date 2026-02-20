<?php

namespace App\Repositories;

use App\Models\SystemSetting;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;

class SystemSettingRepository extends BaseRepository implements SystemSettingRepositoryInterface
{
    public function __construct(SystemSetting $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the single system setting record.
     */
    public function getSettings(): ?SystemSetting
    {
        return $this->model->first();
    }

    /**
     * Update or create the system setting record.
     */
    public function updateSettings(array $data): SystemSetting
    {
        $setting = $this->getSettings();

        if (isset($data['favicon'])) {
            $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->syncFromPath($data['favicon'], $setting?->media_id);
            $data['media_id'] = $media?->id;
        }

        if ($setting) {
            $setting->update($data);
        } else {
            $setting = $this->model->create($data);
        }

        // Clear cache
        $this->model::clearCache();

        return $setting;
    }

    /**
     * Get cached system settings.
     */
    public function getCachedSettings(): ?SystemSetting
    {
        return $this->model::getCached();
    }
}
