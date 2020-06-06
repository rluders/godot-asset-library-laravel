<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\AssetReview;

/**
 * StoreAssetReviewAction Action.
 */
class StoreAssetReviewAction
{
    /**
     * Execute the action.
     */
    public function execute(int $assetId, int $authorId, array $data): AssetReview
    {
        $review = new AssetReview();
        $review->fill($data);

        $review->asset_id = $assetId;
        $review->author_id = $authorId;

        $review->save();

        return $review;
    }
}
