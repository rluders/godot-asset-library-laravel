<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\Asset;

/**
 * StoreAssetAction.
 */
class StoreAssetAction
{
    /**
     * Execute the action.
     *
     * @param int   $authorId Asset author id
     * @param array $data     Asset data
     *
     * @return Asset
     */
    public function execute(int $authorId, array $data): Asset
    {
        // Remove submodel information from the input array as we don't want it here
        $assetData = $data;
        unset($assetData['versions'], $assetData['previews']);

        $asset = new Asset();
        $asset->fill($assetData);

        // The user must be authenticated to submit an asset.
        $asset->author_id = $authorId;

        // Save the asset without its submodels, so that submodels can be saved.
        // This must be done *before* creating submodels, otherwise the asset ID
        // can't be fetched by Eloquent.
        $asset->save();

        // Create and save the version and preview submodels
        if (array_key_exists('versions', $data)) {
            $asset->versions()->createMany($data['versions']);
        }

        if (array_key_exists('previews', $data)) {
            $asset->previews()->createMany($data['previews']);
        }

        // Save the asset with its submodels
        $asset->save();

        return $asset;
    }
}
