<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\StoreAssetReviewReplyAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAssetReviewReplyActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if reply can be stored.
     *
     * @return void
     */
    public function testCanStore()
    {
        $users = factory(\App\Models\User::class, 2)->create();

        $asset = factory(\App\Models\Asset::class)
            ->create(['author_id' => $users[0]->id]);

        $review = factory(\App\Models\AssetReview::class)
            ->create(['asset_id' => $asset->asset_id, 'author_id' => $users[1]->id]);

        $faker = \Faker\Factory::create();

        $data = ['comment' => $faker->text(250)];

        $action = new StoreAssetReviewReplyAction();
        $reply = $action->execute((int) $review->id, $data);

        $this->assertInstanceOf(\App\Models\AssetReviewReply::class, $reply);
        $this->assertDatabaseHas(
            'asset_review_replies',
            ['asset_review_id' => $asset->asset_id]
        );
    }
}
