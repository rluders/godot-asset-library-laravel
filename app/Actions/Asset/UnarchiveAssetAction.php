<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;

/**
 * UnarchiveAssetAction Action.
 */
class UnarchiveAssetAction
{
    /**
     * Execute the action.
     */
    public function execute(Asset $asset): Asset
    {
        if ($asset->is_archived) {
            $asset->is_archived = false;
            $asset->save();
        }

        return $asset;
    }
}
