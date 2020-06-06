<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Asset\ArchiveAssetAction;
use App\Actions\Asset\PublishAssetAction;
use App\Actions\Asset\StoreAssetAction;
use App\Actions\Asset\UnarchiveAssetAction;
use App\Actions\Asset\UnpublishAssetAction;
use App\Actions\Asset\UpdateAssetAction;
use App\Http\Requests\ListAssets;
use App\Http\Requests\SubmitAsset;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AssetController extends Controller
{
    /**
     * Display a paginated list of assets.
     */
    public function index(ListAssets $request): View
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? Asset::ASSETS_PER_PAGE;
        $page = $validated['page'] ?? 1;

        $assets = Asset::with('author', 'versions')->filterSearch($validated);

        $sliced = $assets->slice(
            intval(($page - 1) * $itemsPerPage),
            $itemsPerPage
        )->values();

        $paginator = new LengthAwarePaginator(
            $sliced,
            $assets->count(),
            $itemsPerPage
        );

        return view('asset.index', ['assets' => $paginator]);
    }

    /**
     * Display a single asset.
     */
    public function show(Asset $asset): View
    {
        $asset->reviews->load(['asset', 'author', 'reply']);

        return view('asset.show', ['asset' => $asset]);
    }

    /**
     * Display the form used to submit an asset.
     */
    public function create(): View
    {
        return view('asset.create', ['editing' => false]);
    }

    /**
     * Insert a newly created asset into the database.
     */
    public function store(
        SubmitAsset $request,
        StoreAssetAction $action
    ): RedirectResponse {
        $data = $request->validated();
        // Execute the action to create the asset
        $asset = $action->execute((int) auth()->id(), $data);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('Your asset “:asset” has been submitted!', ['asset' => $asset->title])
        );

        $author = auth()->user();
        info("$author created the asset $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Display the form used to edit an asset.
     */
    public function edit(Asset $asset): View
    {
        // @FIXME It is not a good practice - unnecessarily increases the template complexity
        return view('asset.create', ['editing' => true, 'asset' => $asset]);
    }

    /**
     * Store modifications to an existing asset.
     */
    public function update(
        Asset $asset,
        SubmitAsset $request,
        UpdateAssetAction $action
    ): RedirectResponse {
        $data = $request->validated();
        // Execute the action to update the asset
        $asset = $action->execute($asset, $data);

        $author = auth()->user();
        info("$author updated the asset $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Publishes an asset (only effective if it has been unpublished).
     * This can only be done by an administrator.
     * Once published, the asset will be visible in the list of assets again.
     */
    public function publish(
        Asset $asset,
        Request $request,
        PublishAssetAction $action
    ): RedirectResponse {
        // Execute the action to publish the asset
        $asset = $action->execute($asset);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash('status', __('The asset is now public again.'));

        $admin = auth()->user();
        info("$admin unpublished $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Unpublishes an asset. This can only be done by an administrator.
     * Once unpublished, the asset will no longer appear in the list of assets.
     */
    public function unpublish(
        Asset $asset,
        Request $request,
        UnpublishAssetAction $action
    ): RedirectResponse {
        // Execute the action to unpublish the asset
        $asset = $action->execute($asset);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash('status', __('The asset is no longer public.'));

        $admin = auth()->user();
        info("$admin published $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Mark an asset as archived. This can be done by its author or an administrator.
     * Once an asset is archived, it can no longer receive any reviews.
     * The asset can be unarchived at any time by its author or an administrator.
     */
    public function archive(
        Asset $asset,
        Request $request,
        ArchiveAssetAction $action
    ): RedirectResponse {
        // Execute the action to archive the asset
        $asset = $action->execute($asset);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __(
                'The asset is now marked as archived.
                 Users can no longer leave reviews, but it
                 can still be downloaded.'
            )
        );

        $user = auth()->user();
        info("$user archived $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Mark an asset as unarchived.
     * This can be done by its author or an administrator.
     */
    public function unarchive(
        Asset $asset,
        Request $request,
        UnarchiveAssetAction $action
    ): RedirectResponse {
        // Execute the action to unarchive the asset
        $asset = $action->execute($asset);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The asset is no longer marked as archived. Welcome back!')
        );

        $user = auth()->user();
        info("$user unarchived $asset.");

        return redirect(route('asset.show', $asset));
    }
}
