<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\UnpublishAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnpublishAssetActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if asset can be unpublished.
     */
    public function testCanUnpublish()
    {
        $user = factory(\App\Models\User::class)->create();
        $asset = factory(\App\Models\Asset::class)
            ->state('published')
            ->create(['author_id' => $user->id]);

        $action = new UnpublishAssetAction();
        $unpublishedAsset = $action->execute($asset);

        $this->assertFalse($unpublishedAsset->is_published);
    }
}
