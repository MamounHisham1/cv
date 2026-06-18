<?php

namespace App\Ai\Tools\Telegram;

use App\Models\Referral;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetReferralStats implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get referral program statistics: total referrals, signup rewards granted, purchase rewards granted, conversion rate.';
    }

    public function handle(Request $request): Stringable|string
    {
        $total = Referral::count();
        $distinctReferred = Referral::distinct('referred_id')->count('referred_id');
        $signupRewarded = Referral::where('signup_rewarded', true)->count();
        $purchaseRewarded = Referral::where('purchase_rewarded', true)->count();

        $conversionRate = $total > 0
            ? round(($purchaseRewarded / $total) * 100, 1)
            : 0.0;

        return <<<TEXT
=== Referral Statistics ===

Total referrals: {$total}
Distinct users referred: {$distinctReferred}
Signup rewards granted: {$signupRewarded}
Purchase rewards granted: {$purchaseRewarded}
Conversion rate (purchase-rewarded): {$conversionRate}%
TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
