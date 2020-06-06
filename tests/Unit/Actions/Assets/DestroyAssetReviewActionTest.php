<?php

namespace Tests\Unit\Actions\Assets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyAssetReviewActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if can destroy the assert review.
     */
    public function testCanDestroy()
    {
        $user = factory(\App\Models\User::class)->create();
        $asset = factory(\App\Models\Asset::class)
            ->create(['author_id' => $user->id]);

        $review = factory(\App\Models\AssetReview::class)->create(
            [
                'asset_id' => $asset->asset_id,
                'author_id' => $user->id,
            ]
        );

        $reviewId = $review->id;
        $review->delete();

        $this->assertNull(\App\Models\AssetReview::find($reviewId));
    }
}
