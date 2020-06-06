<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\ArchiveAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiveAssetActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if asset can be archived.
     */
    public function testCanArchive()
    {
        $user = factory(\App\Models\User::class)->create();
        $asset = factory(\App\Models\Asset::class)
            ->state('unarchived')
            ->create(['author_id' => $user->id]);

        $action = new ArchiveAssetAction();
        $archivedAsset = $action->execute($asset);

        $this->assertTrue($archivedAsset->is_archived);
    }
}
