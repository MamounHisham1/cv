<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;

class ReferralService
{
    public function generateCodeForUser(User $user): ReferralCode
    {
        return ReferralCode::firstOrCreate(
            ['user_id' => $user->id],
            ['code' => ReferralCode::generateUniqueCode()],
        );
    }

    public function getCodeForUser(User $user): ?ReferralCode
    {
        return ReferralCode::where('user_id', $user->id)->first();
    }

    public function getReferralLink(User $user): string
    {
        $code = $this->getCodeForUser($user);

        if (! $code) {
            $code = $this->generateCodeForUser($user);
        }

        return url('/register?ref='.$code->code);
    }

    public function processReferralOnRegistration(User $newUser, ?string $refCode): void
    {
        $creditManager = app(CreditManager::class);

        $creditManager->grantMonthlyCredits($newUser);

        if ($refCode) {
            $referralCode = ReferralCode::where('code', $refCode)->first();

            if ($referralCode && $referralCode->user_id !== $newUser->id) {
                $referrer = $referralCode->user;

                $alreadyReferred = Referral::where('referred_id', $newUser->id)->exists();

                if (! $alreadyReferred) {
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_id' => $newUser->id,
                        'signup_rewarded' => true,
                        'purchase_rewarded' => false,
                    ]);

                    $signupReward = config('credits.referrals.signup_reward', 10);
                    $creditManager->add($referrer, $signupReward, 'referral_signup', [
                        'referred_user_id' => $newUser->id,
                        'referred_user_name' => $newUser->name,
                        'referral_code' => $refCode,
                    ]);

                    $inviteeBonus = config('credits.referrals.invitee_signup_bonus', 8);
                    $creditManager->add($newUser, $inviteeBonus, 'invitee_signup_bonus', [
                        'referral_code' => $refCode,
                        'referrer_user_id' => $referrer->id,
                    ]);
                }
            }
        }
    }
}
