<?php

namespace App\Repositories\Contracts;

use App\Models\SystemSetting;

interface SystemSettingRepositoryInterface extends RepositoryInterface
{
    /**
     * Get the single system setting record.
     */
    public function getSettings(): ?SystemSetting;

    /**
     * Update or create the system setting record.
     */
    public function updateSettings(array $data): SystemSetting;

    /**
     * Get cached system settings.
     */
    public function getCachedSettings(): ?SystemSetting;
}
