<?php

declare(strict_types=1);

namespace App\Actions\Asset;

use App\Models\AssetReviewReply;

/**
 * StoreAssetReviewReplyAction Action.
 */
class StoreAssetReviewReplyAction
{
    /**
     * Execute the action.
     *
     * @param int   $reviewId Asset review id
     * @param array $data     Review reply data
     *
     * @return AssetReviewReply
     */
    public function execute(int $reviewId, array $data): AssetReviewReply
    {
        $reviewReply = new AssetReviewReply();
        $reviewReply->fill($data);
        $reviewReply->asset_review_id = $reviewId;
        $reviewReply->save();

        return $reviewReply;
    }
}
