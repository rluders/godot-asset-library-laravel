<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;

/**
 * ArchiveAssetAction Action.
 */
class ArchiveAssetAction
{
    /**
     * Execute the action.
     */
    public function execute(Asset $asset): Asset
    {
        if (! $asset->is_archived) {
            $asset->is_archived = true;
            $asset->save();
        }

        return $asset;
    }
}
