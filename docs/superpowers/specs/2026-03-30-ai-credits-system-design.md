# AI Credits System Design

## Overview

A prepaid credit system for AI operations (CV evaluation, CV parsing, CV builder chat). Users spend credits proportional to actual token usage. Credits can be earned through referrals or purchased (future).

## Plans

| Plan | Monthly Credits | Free Builder Messages | Price |
|------|----------------|----------------------|-------|
| Free | 30 | 5 per conversation | $0 |
| Pro | 100 | 50 per conversation | TBD (Stripe, future) |
| Enterprise | 500 | Unlimited | TBD (Stripe, future) |

Monthly credits **do not stack** — balance resets to the plan's monthly amount on renewal.

## Credit Calculation (Token-Based)

Credits are calculated from actual token usage after each AI call:

```
credits_used = ceil((promptTokens + completionTokens) / token_unit) * token_rate
```

Configurable in `config/credits.php`:

```php
'token_rate' => 1,        // 1 credit per token_unit
'token_unit' => 1000,     // token divisor
'markup' => 3,            // 3x markup on actual cost (configurable for when payment is added)
```

### Estimated costs per operation

| Operation | Short CV | Long CV |
|-----------|----------|---------|
| CV Evaluation | ~2 credits | ~4.5 credits |
| CV Parse | ~2 credits | ~5 credits |
| Builder Message (beyond free cap) | ~1-3 credits | ~4-8 credits |

### Overage handling

- AI calls are **never blocked or discarded** after completion
- If credits needed > current balance, deduct only what's available and set balance to **0**
- The user always gets their AI response
- Next call is blocked with "insufficient credits" message
- This is bounded because `MaxTokens` is capped on all agents (4096/8192)

### Builder chat free messages

- First N messages per conversation are free (N depends on plan)
- Messages beyond the cap cost normal token-based credits
- Counter resets with each new conversation (tracked via `agent_conversations`)

## Referral System

Users generate a unique referral link: `https://yourapp.com/register?ref=ABC123`

The referral code is stored in the database; the link is just the URL with the code appended.

### Rewards

| Event | Referrer | Invitee |
|-------|----------|---------|
| Signup | +10 credits | +8 credits |
| First purchase | +15 credits | +15 credits |

### Flow

**On registration:**
1. Check session for `ref` parameter
2. If found, look up `ReferralCode` -> get referrer
3. Create `referrals` record
4. `CreditManager::add(referrer, 10, 'referral_signup')`
5. `CreditManager::add(newUser, 8, 'invitee_signup_bonus')`
6. Grant monthly plan credits for new user

**On first purchase (future, when Stripe is added):**
1. Check if user has an unredeemed referral (`purchase_rewarded = false`)
2. `CreditManager::add(referrer, 15, 'referral_purchase_bonus')`
3. `CreditManager::add(user, 15, 'invitee_purchase_bonus')`
4. Mark `purchase_rewarded = true`

### Abuse prevention

- One referral code per user
- One referral per invitee (cannot be referred twice)
- Referral code must be used during registration only
- Purchase bonus only triggers once per referral

## Database Schema

### `credit_balances`

Single row per user. Tracks current balance and plan.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK -> users, unique |
| balance | integer | Current credits (default: 0) |
| plan | string | `free`, `pro`, `enterprise` (default: `free`) |
| monthly_credits_reset_at | timestamp | When monthly credits were last reset |
| created_at | timestamp | |
| updated_at | timestamp | |

### `credit_transactions`

Every credit movement is logged here. Immutable audit trail.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK -> users |
| amount | integer | Positive = credit added, negative = credit spent |
| type | string | Enum: `monthly_grant`, `referral_signup`, `invitee_signup_bonus`, `referral_purchase_bonus`, `invitee_purchase_bonus`, `ai_evaluation`, `ai_parse`, `ai_builder_message`, `admin_adjustment`, `purchase` |
| reference_type | string, nullable | Polymorphic (e.g., `CvEvaluation::class`) |
| reference_id | bigint, nullable | e.g., the cv_evaluation ID |
| metadata | json, nullable | Token counts, model used, referral code, etc. |
| created_at | timestamp | |
| updated_at | timestamp | |

### `referral_codes`

Each user gets one shareable referral code.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK -> users, unique |
| code | string | Unique 6-character code (e.g., `ABC123`) |
| created_at | timestamp | |
| updated_at | timestamp | |

### `referrals`

Tracks who invited whom and reward status.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| referrer_id | bigint | FK -> users (the inviter) |
| referred_id | bigint | FK -> users (the invitee) |
| signup_rewarded | boolean | Whether referrer got signup credits |
| purchase_rewarded | boolean | Whether referrer got purchase bonus |
| created_at | timestamp | |
| updated_at | timestamp | |

No changes to existing tables.

## Architecture: CreditManager Service

`App\Services\CreditManager` — single class handling all credit operations.

### Methods

| Method | Purpose |
|--------|---------|
| `getBalance(User $user)` | Returns current credit balance |
| `hasCredits(User $user)` | Check if balance > 0 |
| `deduct(User $user, int $credits, string $type, ?Model $reference, array $metadata)` | Deduct credits (caps at current balance), log transaction |
| `add(User $user, int $credits, string $type, array $metadata)` | Add credits, log transaction |
| `calculateFromUsage(Usage $usage)` | Convert token counts to credit amount using config rate |
| `canPerformOperation(User $user, string $operation)` | Check balance + operation-specific rules (free builder messages) |

### Concurrency safety

Use `lockForUpdate()` on `credit_balances` inside a DB transaction to prevent race conditions from simultaneous AI calls.

### Fallback for zero token counts

If Ollama returns `0` for token usage, charge a configurable minimum per operation type and log a warning.

## Integration Points

### CvEvaluator (`app/Livewire/CvEvaluator.php`)

In the `evaluate()` method:
1. Check `CreditManager::hasCredits()` before calling AI
2. If no credits, show "insufficient credits" message, do not call AI
3. Call AI as normal
4. `$credits = CreditManager::calculateFromUsage($response->usage)`
5. `CreditManager::deduct(user, credits, 'ai_evaluation', $cvEvaluation, ['prompt_tokens' => ..., 'completion_tokens' => ...])`

### CV Parser

Same pattern as evaluator — wherever parsing is triggered.

### CV Builder Chat

Wherever builder messages are handled:
1. Check `CreditManager::canPerformOperation(user, 'ai_builder_message')`
2. Count messages in current conversation
3. If within free cap, allow without deduction
4. If beyond free cap, check balance and deduct after call

## Monthly Renewal

A scheduled Artisan command runs daily. For users whose `monthly_credits_reset_at` is older than their plan's renewal period (30 days):

1. Set balance to the plan's monthly credit amount (do not stack)
2. Update `monthly_credits_reset_at` to now
3. Log as `monthly_grant` transaction

## UI

### Credit balance display

- **Navbar**: credit coin icon + balance number, updates via Livewire
- **Builder chat**: inline credit indicator that updates after each message
- Custom gold coin SVG component: `<x-credit-coin size="sm" />`

### Insufficient credits

Friendly message: "You're out of credits. Invite friends to earn more, or upgrade your plan."
Link to referral page.

### Referral page

- User's referral link with copy button
- Stats: total referrals, credits earned from referrals
- List of recent referrals

### Credit history

List of all transactions (earned/spent) with type, amount, and date.

## Configuration (`config/credits.php`)

```php
return [
    'token_rate' => 1,
    'token_unit' => 1000,
    'markup' => 3,

    'minimum_charge' => [
        'ai_evaluation' => 1,
        'ai_parse' => 1,
        'ai_builder_message' => 1,
    ],

    'plans' => [
        'free' => [
            'monthly_credits' => 30,
            'free_builder_messages' => 5,
        ],
        'pro' => [
            'monthly_credits' => 100,
            'free_builder_messages' => 50,
        ],
        'enterprise' => [
            'monthly_credits' => 500,
            'free_builder_messages' => null,
        ],
    ],

    'referrals' => [
        'signup_reward' => 10,
        'invitee_signup_bonus' => 8,
        'purchase_bonus' => 15,
        'invitee_purchase_bonus' => 15,
    ],
];
```

## Phase 1 Scope (Current)

- Free plan only (monthly renewal)
- Token-based credit deduction on all AI operations
- Referral system (signup rewards)
- Credit balance in navbar and builder chat
- Credit history page
- Referral page
- Monthly reset command
- No payment provider (Stripe added later)

## Phase 2 (Future)

- Pro and Enterprise plans with Stripe
- Credit pack purchases
- Purchase-based referral bonuses
- Admin dashboard for credit management
