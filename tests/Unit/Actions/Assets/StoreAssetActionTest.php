<?php

namespace Tests\Unit\Actions\Assets;

use App\Actions\Asset\StoreAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAssetActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can store an asset.
     *
     * @return void
     */
    public function testCanStoreAsset()
    {
        $user = factory(\App\Models\User::class)->create();

        $faker = \Faker\Factory::create();

        // Hexadecimal color code of the form `ff22ff`
        $colorHex = str_pad(
            dechex($faker->numberBetween(0, 16777216)),
            6,
            '0',
            STR_PAD_LEFT
        );

        $data = [
            'title' => 'Asset Store Test',
            'blurb' => rtrim($faker->text(60), '.'),
            'category_id' => $faker->numberBetween(0, \App\Models\Asset::CATEGORY_MAX - 1),
            'cost' => $faker->randomElement(
                [
                    'MIT',
                    'GPL-3.0-or-later',
                    'MPL-2.0',
                    'CC-BY-SA-4.0',
                ]
            ),
            'support_level_id' => $faker->numberBetween(0, \App\Models\Asset::SUPPORT_LEVEL_MAX - 1),
            'description' => $faker->text(500),
            'tags' => $faker->words($faker->numberBetween(0, 8)),
            'browse_url' => 'https://github.com/user/asset',
            // 50% chance of having a changelog and donation link set (as they're optional)
            'changelog_url' => $faker->boolean() ? 'https://github.com/user/asset/blob/master/CHANGELOG.md' : null,
            'donate_url' => $faker->boolean() ? 'https://patreon.com/user' : null,
            'icon_url' => "https://via.placeholder.com/128x128/$colorHex.png",
        ];

        $action = new StoreAssetAction();
        $asset = $action->execute($user->id, $data);

        $this->assertInstanceOf(\App\Models\Asset::class, $asset);
        $this->assertDatabaseHas('assets', ['title' => 'Asset Store Test']);
    }
}
