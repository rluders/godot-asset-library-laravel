<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\StoreAssetReviewAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAssetReviewActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can store an asset review.
     *
     * @return void
     */
    public function testCanStore()
    {
        $users = factory(\App\Models\User::class, 2)->create();

        $asset = factory(\App\Models\Asset::class)
            ->create(['author_id' => $users[0]->id]);

        $faker = \Faker\Factory::create();

        $data = [
            'is_positive' => $faker->boolean(75),
            'comment' => $faker->text(250),
        ];

        $action = new StoreAssetReviewAction();
        $review = $action->execute(
            (int) $asset->asset_id,
            (int) $users[1]->id,
            $data
        );

        $this->assertInstanceOf(\App\Models\AssetReview::class, $review);
        $this->assertDatabaseHas(
            'asset_reviews',
            ['asset_id' => $asset->asset_id, 'author_id' => $users[1]->id]
        );
    }
}
