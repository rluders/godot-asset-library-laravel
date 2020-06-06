<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\PublishAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishAssetActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if asset can be published.
     */
    public function testCanPublish()
    {
        $user = factory(\App\Models\User::class)->create();
        $asset = factory(\App\Models\Asset::class)
            ->state('unpublished')
            ->create(['author_id' => $user->id]);

        $action = new PublishAssetAction();
        $publishedAsset = $action->execute($asset);

        $this->assertTrue($publishedAsset->is_published);
    }
}
