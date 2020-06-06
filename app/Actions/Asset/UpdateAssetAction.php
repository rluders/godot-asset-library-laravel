<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;
use App\Models\AssetPreview;
use App\Models\AssetVersion;

/**
 * UpdateAssetAction.
 */
class UpdateAssetAction
{
    /**
     * Execute the action.
     */
    public function execute(Asset $asset, array $data): Asset
    {
        // Remove submodel information from the input array as we don't want it here.
        // Instead, we update (or create) submodels a few lines below.
        $assetData = $data;
        unset($assetData['versions'], $assetData['previews']);

        $asset->fill($assetData);

        if (array_key_exists('versions', $data)) {
            foreach ($data['versions'] as $version) {
                $version['asset_id'] = $asset->asset_id;
                // Prototypes don't have an ID associated, so we fall back to -1 (which will never match)
                AssetVersion::updateOrCreate(
                    ['id' => $version['id'] ?? -1],
                    $version
                );
            }
        }

        if (array_key_exists('previews', $data)) {
            foreach ($data['previews'] as $preview) {
                $preview['asset_id'] = $asset->asset_id;
                // Prototypes don't have an ID associated, so we fall back to -1 (which will never match)
                AssetPreview::updateOrCreate(
                    ['preview_id' => $preview['id'] ?? -1],
                    $preview
                );
            }
        }

        $asset->save();

        return $asset;
    }
}
