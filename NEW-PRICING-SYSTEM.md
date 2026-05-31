# New Pricing System — Credit-Based Billing

## Core Economics

- **1 credit = $0.06** (derived from Pro plan: 100 credits / $6)
- **Interview API cost: $4.60/hr = $0.077/min** (Deepgram Voice Agent)
- **Evaluation cost: ~$0.01–0.03 per evaluation** (LLM API call on transcript)
- **AI Chat cost: ~$0.002–0.005 per message** (LLM token-based)
- **CV Parse cost: negligible** (one-time parsing API call)

## Credit Rates Per Feature

| Feature | Rate | User Cost | Our Cost | Margin |
|---------|------|-----------|----------|--------|
| AI Interview | 3 credits/min | $0.18/min | $0.077/min | ~130% |
| Interview Evaluation | 5 credits (flat) | $0.30 | ~$0.02 | ~1400% |
| AI Chat Message | 1 credit | $0.06 | ~$0.004 | ~1400% |
| CV Parse | 5 credits (one-time) | $0.30 | ~$0.01 | ~2900% |

## Plans

| Plan | Monthly Credits | Price | 1 Credit = | Notes |
|------|----------------|-------|-------------|-------|
| Free | 30 | $0 | — | 1 free interview (3 min trial), limited AI chat |
| Pro | 100 | $6 | $0.06 | Full access to all features |
| Enterprise | 500 | TBD | TBD | Volume discount, lower per-credit cost |

### Plan Comparison

| | Free | Pro | Enterprise |
|---|---|---|---|
| Monthly credits | 30 | 100 | 500 |
| AI chat messages | 5 free | 100 credits worth | 500 credits worth |
| Free interview trial | 3 minutes | — | — |
| Paid interviews | Blocked | Time-based (3 cr/min) | TBD (possible 2 cr/min discount) |
| CV parsing | — | Included | Included |
| Interview evaluations | — | Included | Included |

## Interview Session Costs (Time-Based)

Charging a **flat rate per interview** is risky — session length varies wildly. A user on a 45-minute call costs us $3.45 but only generates 5 credits of revenue with flat pricing. Time-based charging aligns cost with usage.

### Pro Plan (3 credits/min)

| Session Length | Credits Charged | User Cost | Our Cost | Profit |
|---------------|-----------------|-----------|-----------|--------|
| 3 min (free trial) | 0 (free) | $0 | $0.23 | -$0.23 (acquisition cost) |
| 5 min | 15 | $0.90 | $0.38 | $0.52 |
| 10 min | 30 | $1.80 | $0.77 | $1.03 |
| 15 min | 45 | $2.70 | $1.15 | $1.55 |
| 30 min | 90 | $5.40 | $2.30 | $3.10 |

### Free Trial Strategy

The 3-minute free trial costs us ~$0.23 per user. This is an **acquisition cost** — the trial exists to convert free users to paid. Key metrics to track:

- Free trial → Pro conversion rate
- Average session length (paid users)
- Credits remaining at month end (are they hitting the limit?)

## How Pro Users Spend Their 100 Credits

A Pro user at $6/month with 100 credits:

| Usage Pattern | Credits Used | Remaining |
|--------------|-------------|-----------|
| 3 interviews × 10 min each (90 cr) + 1 evaluation (5 cr) | 95 | 5 |
| 2 interviews × 15 min each (90 cr) + evaluation (5 cr) | 95 | 5 |
| 1 interview × 10 min (30 cr) + 2 evaluations (10 cr) + 40 AI chat msgs (40 cr) | 80 | 20 |
| 50 AI chat messages only | 50 | 50 |

**Key insight:** Interview-heavy users burn through credits fast (~3-4 interviews/month). This creates natural upsell pressure to Enterprise.

## Implementation Notes

### Time-Based Interview Charging

1. Reserve an estimated amount upfront (e.g., 30 credits = 10 min cap)
2. Track actual `duration_seconds` on `InterviewSession`
3. On session end, settle actual charges: `ceil(duration_minutes) * credits_per_minute + evaluation_fee`
4. Refund unused reserved credits

The `reserve()` → `settle()` → `cancelReservation()` pattern in `CreditManager` already supports this.

### Config Structure

```php
// config/credits.php

'minimum_charge' => [
    'ai_interview_per_minute' => 3,   // credits per minute of interview
    'interview_evaluation' => 5,       // flat fee per evaluation
    'ai_chat_message' => 1,            // per AI chat message
    'cv_parse' => 5,                   // one-time CV import
],

'plans' => [
    'free' => [
        'monthly_credits' => 30,
        'free_builder_messages' => 5,
    ],
    'pro' => [
        'monthly_credits' => 100,
    ],
    'enterprise' => [
        'monthly_credits' => 500,
    ],
],

'interview' => [
    'free_trial_minutes' => 3,
    'reserve_minutes' => 10,          // max minutes to reserve upfront
    'grace_period_seconds' => 30,
],
```

### Evaluation Bundling

Two approaches:

1. **Bundled (recommended)** — Interview charge = time credits + evaluation fee. Show as one total: "Interview: 35 credits (30 min + 5 evaluation)"
2. **Separate** — Charge time at session end, charge evaluation when job completes. More transparent but more complex UX.

Bundled is cleaner. The evaluation always runs after an interview, so there's no case where you charge time but not evaluation.

### Credits as Low-Balance Warnings

Current threshold: 5 credits. With time-based charging, a user with 5 credits can only do ~1.7 minutes of interview. Consider:

- Warn at **15 credits** — enough for a short 5-min interview
- Suggest upgrading when below threshold
- Don't start interviews if remaining credits < minimum charge (already implemented)

## Enterprise Pricing Considerations

Not yet finalized. Options:

| Enterprise Option | Monthly Price | 1 Credit = | Interview Rate |
|------------------|---------------|-------------|----------------|
| Budget | $15 | $0.03 | 3 cr/min (same) |
| Standard | $20 | $0.04 | 2 cr/min (discount) |
| Premium | $25 | $0.05 | 2 cr/min (discount) |

Giving Enterprise users **2 credits/min** instead of 3 is a tangible perk that doesn't cost us anything extra (our cost is the same). It's pure perceived value.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| User starts 60-min interview | Costs us $4.60, only get 180 credits | Reserve cap at 10-15 min upfront; warn user if approaching limit |
| Free trial abuse (multiple accounts) | Acquisition cost per fake account | Gate on email verification + device fingerprint; limit 1 trial per email domain |
| API price increase from Deepgram | Margins shrink | Monitor costs monthly; adjust credit rates in config without code changes |
| Enterprise users consuming too much | LLM/API costs exceed subscription | Rate limits per plan; fair use policy |

## What Needs To Change From Current Code

1. **`endInterview()`** — switch from flat 5-credit charge to `ceil(duration_minutes) * 3 + 5` (evaluation)
2. **`startInterview()`** — reserve estimated credits (e.g., 30 = 10 min cap) instead of nothing
3. **`config/credits.php`** — add `ai_interview_per_minute` key, update `ai_interview` to `interview_evaluation`
4. **UI** — show estimated cost before starting interview ("This will cost ~3 credits/minute")
5. **Low balance threshold** — raise from 5 to 15 so users aren't blocked mid-feature
