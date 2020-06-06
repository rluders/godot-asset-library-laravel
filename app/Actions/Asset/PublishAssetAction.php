<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;

/**
 * PublishAssetAction.
 */
class PublishAssetAction
{
    /**
     * Execute the action.
     */
    public function execute(Asset $asset): Asset
    {
        if (! $asset->is_published) {
            $asset->is_published = true;
            $asset->save();
        }

        return $asset;
    }
}
