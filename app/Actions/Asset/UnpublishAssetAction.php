<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;

/**
 * UnpublishAssetAction Action.
 */
class UnpublishAssetAction
{
    /**
     * Execute the action.
     */
    public function execute(Asset $asset): Asset
    {
        if ($asset->is_published) {
            $asset->is_published = false;
            $asset->save();
        }

        return $asset;
    }
}
