<?php

namespace App\Http\Controllers;

use App\Actions\Asset\DestroyAssetReviewAction;
use App\Actions\Asset\StoreAssetReviewAction;
use App\Actions\Asset\StoreAssetReviewReplyAction;
use App\Actions\Asset\UpdateAssetReviewAction;
use App\Http\Requests\SubmitReview;
use App\Http\Requests\SubmitReviewReply;
use App\Models\Asset;
use App\Models\AssetReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Insert a newly created review into the database.
     */
    public function store(
        Asset $asset,
        SubmitReview $request,
        StoreAssetReviewAction $action
    ): RedirectResponse {
        $data = $request->validated();
        // Execute the action to create the review
        $review = $action->execute($asset->asset_id, (int) auth()->id(), $data);

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __(
                'Your review for “:asset” has been posted!',
                ['asset' => $asset->title]
            )
        );

        $user = auth()->user();
        $rating = $review->is_positive ? 'positive' : 'negative';

        $log = "$user submitted a $rating review for $asset";

        if ($review->comment) {
            $log .= ' with a comment';
        }

        info("$log.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Update an existing review in the database.
     */
    public function update(
        AssetReview $review,
        SubmitReview $request,
        UpdateAssetReviewAction $action
    ): RedirectResponse {
        $data = $request->validated();
        // Execute the action to update the asset review
        $review = $action->execute($review, $data);

        $asset = $review->asset;
        $user = auth()->user();

        $request->session()->flash('statusType', 'success');

        if ($review->author && $asset && $user) {
            $isAssetOwner = $review->author_id === $user->id;

            $request->session()->flash(
                'status',
                $isAssetOwner
                    ? __(
                        'You edited your review for “:asset”!',
                        ['asset' => $asset->title]
                    ) : __(
                        "You edited :author's review for “:asset”!",
                        [
                            'author' => $review->author->username,
                            'asset' => $asset->title,
                        ]
                    )
            );

            info(
                $isAssetOwner
                ? "$user edited their review for $asset."
                : "$user edited $review->author's review for $asset."
            );
        }

        return redirect(route('asset.show', $asset));
    }

    /**
     * Remove a review from the database.
     * This can only done by its author or an administrator.
     */
    public function destroy(
        AssetReview $review,
        Request $request,
        DestroyAssetReviewAction $action
    ): RedirectResponse {
        $review = $action->execute($review);

        $asset = $review->asset;
        $user = auth()->user();
        $author = $review->author;

        $request->session()->flash('statusType', 'success');

        if ($review->author && $asset && $user && $author) {
            $isOwner = $review->author->id === $user->id;

            $request->session()->flash(
                'status',
                $isOwner
                    ? __(
                        'You removed your review for “:asset”!',
                        ['asset' => $asset->title]
                    ) : __(
                        "You removed :author's review for “:asset”!",
                        [
                            'author' => $author->username,
                            'asset' => $asset->title,
                        ]
                    )
            );

            info(
                $isOwner
                ? "$user removed their review for $asset."
                : "$user removed $author's review for $asset."
            );
        }

        return redirect(route('asset.show', $asset));
    }

    /**
     * Update a review with a reply from the asset author.
     */
    public function storeReviewReply(
        AssetReview $review,
        SubmitReviewReply $request,
        StoreAssetReviewReplyAction $action
    ): RedirectResponse {
        $data = $request->validated();
        $action->execute($review->id, $data);

        if ($review->author) {
            $request->session()->flash('statusType', 'success');
            $request->session()->flash(
                'status',
                __(
                    "Your reply to :author's review has been posted!",
                    ['author' => $review->author->username]
                )
            );

            $author = auth()->user();
            info("$author replied to $review->author's review for $review->asset.");
        }

        return redirect(route('asset.show', $review->asset));
    }
}
