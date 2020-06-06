<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\AssetReview;

/**
 * UpdateAssetReviewAction Action.
 */
class UpdateAssetReviewAction
{
    /**
     * Execute the action.
     *
     * @param AssetReview $review The asset review instance
     * @param array       $data   Asset data be saved
     */
    public function execute(AssetReview $review, array $data): AssetReview
    {
        $review->fill($data);
        $review->save();

        return $review;
    }
}
