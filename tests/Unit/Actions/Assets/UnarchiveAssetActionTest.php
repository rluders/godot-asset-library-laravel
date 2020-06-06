<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\UnarchiveAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnarchiveAssetActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if asset can be unarchived.
     */
    public function testCanUnarchive()
    {
        $user = factory(\App\Models\User::class)->create();
        $asset = factory(\App\Models\Asset::class)->state('archived')->make();
        $user->assets()->save($asset);

        $action = new UnarchiveAssetAction();
        $archivedAsset = $action->execute($asset);

        $this->assertFalse($archivedAsset->is_archived);
    }
}
