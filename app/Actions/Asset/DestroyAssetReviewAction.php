<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\AssetReview;

/**
 * DestroyAssetReviewAction Action.
 */
class DestroyAssetReviewAction
{
    /**
     * Execute the action.
     */
    public function execute(AssetReview $review): AssetReview
    {
        $deleted = $review->load(['asset', 'author']);

        $review->delete();

        return $deleted;
    }
}
