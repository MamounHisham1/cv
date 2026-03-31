# AI Credits System — Implementation Plan

**Spec**: `docs/superpowers/specs/2026-03-30-ai-credits-system-design.md`
**Scope**: Phase 1 only (Free plan + referrals, no Stripe)

---

## Phase 1: Foundation (Database + Models + Config)

### Step 1.1 — Create config file
**File**: `config/credits.php`
- Token rate settings (`token_rate`, `token_unit`, `markup`)
- Minimum charge per operation type
- Plan definitions (free/pro/enterprise with monthly credits and free builder messages)
- Referral reward amounts
- Copy values exactly from spec

### Step 1.2 — Create migrations
**Files** (in order):
1. `database/migrations/xxxx_xx_xx_xxxxxx_create_credit_balances_table.php`
   - `id`, `user_id` (foreignId, unique), `balance` (integer, default 0), `plan` (string, default 'free'), `monthly_credits_reset_at` (timestamp, nullable), timestamps
2. `database/migrations/xxxx_xx_xx_xxxxxx_create_credit_transactions_table.php`
   - `id`, `user_id` (foreignId), `amount` (integer), `type` (string), `reference_type` (string, nullable), `reference_id` (bigint, nullable), `metadata` (json, nullable), timestamps
3. `database/migrations/xxxx_xx_xx_xxxxxx_create_referral_codes_table.php`
   - `id`, `user_id` (foreignId, unique), `code` (string, unique), timestamps
4. `database/migrations/xxxx_xx_xx_xxxxxx_create_referrals_table.php`
   - `id`, `referrer_id` (foreignId), `referred_id` (foreignId), `signup_rewarded` (boolean, default false), `purchase_rewarded` (boolean, default false), timestamps

Run `php artisan migrate` after creating all migrations.

### Step 1.3 — Create models
**Files**:
1. `app/Models/CreditBalance.php`
   - Fillable: `user_id`, `balance`, `plan`, `monthly_credits_reset_at`
   - Casts: `balance` => `integer`, `monthly_credits_reset_at` => `datetime`
   - Relationships: `belongsTo(User)`, `hasMany(CreditTransaction)`
   - HasFactory
2. `app/Models/CreditTransaction.php`
   - Fillable: `user_id`, `amount`, `type`, `reference_type`, `reference_id`, `metadata`
   - Casts: `amount` => `integer`, `metadata` => `array`
   - Relationship: `belongsTo(User)`, `morphTo` for reference
   - HasFactory
3. `app/Models/ReferralCode.php`
   - Fillable: `user_id`, `code`
   - Relationship: `belongsTo(User)`
   - HasFactory
   - Accessor/mutator for generating unique 6-char code
4. `app/Models/Referral.php`
   - Fillable: `referrer_id`, `referred_id`, `signup_rewarded`, `purchase_rewarded`
   - Casts: booleans
   - Relationships: `belongsTo(User, 'referrer_id')`, `belongsTo(User, 'referred_id')`
   - HasFactory

### Step 1.4 — Create factories
**Files**:
1. `database/factories/CreditBalanceFactory.php`
2. `database/factories/CreditTransactionFactory.php`
3. `database/factories/ReferralCodeFactory.php`
4. `database/factories/ReferralFactory.php`

### Step 1.5 — Update User model
**File**: `app/Models/User.php`
- Add relationship: `hasOne(CreditBalance)`
- Add relationship: `hasMany(CreditTransaction)`
- Add relationship: `hasOne(ReferralCode)`
- Add helper methods: `creditBalance()`, `hasCredits()`

### Step 1.6 — Write tests for foundation
**File**: `tests/Feature/Credit/CreditBalanceModelTest.php`
- Creating a credit balance for a user
- Default values (balance=0, plan='free')
- Balance increments and decrements
- Plan transitions

**File**: `tests/Feature/Credit/CreditTransactionModelTest.php`
- Creating transactions with different types
- Polymorphic reference tracking
- Metadata storage

**File**: `tests/Feature/Credit/ReferralCodeModelTest.php`
- Generating unique codes
- One code per user

**File**: `tests/Feature/Credit/ReferralModelTest.php`
- Creating referral records
- Reward status tracking

---

## Phase 2: CreditManager Service

### Step 2.1 — Create CreditManager service
**File**: `app/Services/CreditManager.php`

Methods:
- `getBalance(User $user): int` — Returns current balance, creates CreditBalance if missing
- `hasCredits(User $user): bool` — Returns `balance > 0`
- `add(User $user, int $credits, string $type, array $metadata = []): CreditTransaction` — Adds credits in a DB transaction with `lockForUpdate()`, creates transaction record
- `deduct(User $user, int $credits, string $type, ?Model $reference = null, array $metadata = []): CreditTransaction` — Deducts credits, caps at current balance (never goes negative), uses `lockForUpdate()`
- `calculateFromUsage(object $usage): int` — `ceil((promptTokens + completionTokens) / config('credits.token_unit')) * config('credits.token_rate')`, with fallback to minimum charge if tokens = 0
- `canPerformOperation(User $user, string $operation): bool` — Checks balance + operation-specific rules (e.g., free builder messages)
- `getFreeBuilderMessagesRemaining(User $user, ?string $conversationId = null): int` — Counts messages in conversation, returns remaining free messages based on plan
- `grantMonthlyCredits(User $user): CreditTransaction` — Resets balance to plan amount, updates `monthly_credits_reset_at`

### Step 2.2 — Write tests for CreditManager
**File**: `tests/Feature/Credit/CreditManagerTest.php`
- `getBalance` returns 0 for new user
- `add` increases balance and creates transaction
- `deduct` decreases balance, caps at 0
- `deduct` with insufficient balance sets to 0
- `calculateFromUsage` converts tokens correctly
- `calculateFromUsage` falls back to minimum charge when tokens = 0
- `canPerformOperation` for evaluation/parse (always checks balance)
- `canPerformOperation` for builder_message (respects free cap)
- `grantMonthlyCredits` resets balance to plan amount
- Concurrency: two simultaneous deductions don't over-deduct (use `lockForUpdate`)

---

## Phase 3: Referral System

### Step 3.1 — Create ReferralService
**File**: `app/Services/ReferralService.php`

Methods:
- `generateCodeForUser(User $user): ReferralCode` — Creates a unique 6-char code if user doesn't have one
- `getCodeForUser(User $user): ?ReferralCode` — Returns user's referral code
- `processReferralOnRegistration(User $newUser, ?string $refCode): void` — Looks up ref code, creates Referral record, awards credits to both parties, grants monthly credits to new user
- `getReferralLink(User $user): string` — Returns full URL with referral code

### Step 3.2 — Hook into Fortify registration
**File**: `app/Actions/Fortify/CreateNewUser.php`
- After user creation, check session for `ref` parameter
- Call `ReferralService::processReferralOnRegistration($user, session('ref'))`
- Call `CreditManager::grantMonthlyCredits($user)` to give initial monthly credits

### Step 3.3 — Capture referral code from URL
**File**: `app/Http/Middleware/CaptureReferralCode.php`
- Middleware that checks for `?ref=` query parameter
- Stores it in session (`ref`)
- Only captures on GET requests to auth routes
- Register in `app/Http/Kernel.php` or via route middleware

### Step 3.4 — Write tests for referral system
**File**: `tests/Feature/Credit/ReferralServiceTest.php`
- Generating a referral code
- User can only have one referral code
- Processing registration with valid referral code awards credits to both parties
- Processing registration without referral code only grants monthly credits
- Same user can't be referred twice
- Referral link generation

**File**: `tests/Feature/Credit/RegistrationWithReferralTest.php`
- Registering with `?ref=` in URL stores code in session
- Registering processes the referral correctly
- Registering without referral still gets monthly credits

---

## Phase 4: Integrate Credit Checks into AI Operations

### Step 4.1 — Integrate with CvEvaluator
**File**: `app/Livewire/CvEvaluator.php`
- In `evaluate()` method (around line 114):
  - Before AI call: check `CreditManager::hasCredits(auth()->user())`
  - If no credits: set error message "You're out of credits. Invite friends to earn more!" and return
  - After AI call: calculate credits from `$response->usage`, call `CreditManager::deduct()`
  - Store transaction metadata: prompt_tokens, completion_tokens, model used

### Step 4.2 — Integrate with CvParser (CvBuilder import)
**File**: `app/Livewire/CvBuilder.php`
- In `importCv()` method (around line 183):
  - Before AI call: check credits
  - After AI call: calculate and deduct credits
  - Transaction type: `ai_parse`

### Step 4.3 — Integrate with CvParser (CvImporter)
**File**: `app/Livewire/CvImporter.php`
- In `importCv()` method (around line 101):
  - Before AI call: check credits
  - After AI call: calculate and deduct credits
  - Transaction type: `ai_parse`

### Step 4.4 — Integrate with CvAiChat (Builder chat)
**File**: `app/Livewire/CvAiChat.php`
- In `getAiResponse()` method (around line 88):
  - Check `CreditManager::canPerformOperation(user, 'ai_builder_message')`
  - If within free message cap (count messages in conversation): allow without deduction
  - If beyond free cap: check `hasCredits()`, if no credits show error
  - After AI call: if beyond free cap, calculate and deduct credits
  - Transaction type: `ai_builder_message`

### Step 4.5 — Write integration tests
**File**: `tests/Feature/Credit/CvEvaluatorCreditsTest.php`
- Evaluation succeeds when user has credits
- Evaluation is blocked when user has 0 credits
- Credits are deducted after successful evaluation
- Credits are capped at balance (no negative)

**File**: `tests/Feature/Credit/CvParserCreditsTest.php`
- CV parsing succeeds when user has credits
- CV parsing is blocked when user has 0 credits
- Credits deducted after parsing

**File**: `tests/Feature/Credit/CvAiChatCreditsTest.php`
- First N builder messages are free (based on plan config)
- Messages beyond free cap require credits
- Credits deducted after each paid message
- New conversation resets free message counter

---

## Phase 5: Monthly Renewal Command

### Step 5.1 — Create Artisan command
**File**: `app/Console/Commands/ResetMonthlyCredits.php`
- Signature: `credits:reset-monthly`
- Description: "Reset monthly credits for all users whose renewal period has elapsed"
- Logic:
  - Query `credit_balances` where `monthly_credits_reset_at < now() - 30 days`
  - For each: call `CreditManager::grantMonthlyCredits()`
  - Log how many users were reset

### Step 5.2 — Schedule the command
**File**: `routes/console.php`
- Add: `Schedule::command('credits:reset-monthly')->dailyAt('00:00');`

### Step 5.3 — Write tests
**File**: `tests/Feature/Credit/MonthlyResetTest.php`
- Users past 30 days get their balance reset to plan amount
- Users not past 30 days are unaffected
- Transaction is logged with type `monthly_grant`
- Balance does not stack (reset, not add)

---

## Phase 6: UI — Credit Balance Display

### Step 6.1 — Navbar credit balance component
**File**: `app/Livewire/CreditBalanceIndicator.php`
- Livewire component (no layout, just a partial)
- Renders: `<x-credit-coin size="sm" />` + balance number
- Listens for `credits-updated` event to refresh
- Used in the navbar

**File**: `resources/views/livewire/credit-balance-indicator.blade.php`
- Inline pill showing coin icon + balance
- Dark theme, emerald accent styling consistent with app

### Step 6.2 — Add to cv-builder-nav
**File**: `resources/views/components/cv-builder-nav.blade.php`
- Add `<livewire:credit-balance-indicator />` in the navbar, after nav items and before user menu

### Step 6.3 — Add to builder chat
**File**: `resources/views/livewire/cv-ai-chat.blade.php`
- Show credit balance indicator in the chat header/toolbar
- Dispatch `credits-updated` after each AI response in CvAiChat

### Step 6.4 — Insufficient credits UI
**File**: Update error handling in `CvEvaluator`, `CvAiChat`, `CvImporter`
- When blocked, show a friendly Livewire modal or alert:
  - "You're out of credits"
  - "Invite friends to earn more credits" with link to referral page
  - Coin icon

### Step 6.5 — Write tests
**File**: `tests/Feature/Credit/CreditBalanceIndicatorTest.php`
- Component renders for authenticated users
- Shows correct balance
- Updates when `credits-updated` event is dispatched

---

## Phase 7: Referral Page

### Step 7.1 — Create referral page
**File**: `app/Livewire/ReferralDashboard.php`
- Shows user's referral link with copy-to-clipboard button
- Stats: total referrals, credits earned from referrals
- List of recent referrals (referred user name, date, reward status)

**File**: `resources/views/livewire/referral-dashboard.blade.php`
- Dark glassmorphism styling consistent with app
- Sections: referral link card, stats cards, referral history table
- Copy button using Alpine.js

### Step 7.2 — Add route
**File**: `routes/web.php`
- Add: `Route::get('/referrals', ReferralDashboard::class)->name('referrals')->middleware(['auth', 'verified']);`

### Step 7.3 — Add nav link
**File**: `resources/views/components/cv-builder-nav.blade.php`
- Add "Referrals" link in the navbar

### Step 7.4 — Write tests
**File**: `tests/Feature/Credit/ReferralDashboardTest.php`
- Page loads for authenticated users
- Shows referral link with user's code
- Shows correct referral stats
- Copy button works
- Guest cannot access

---

## Phase 8: Credit History Page

### Step 8.1 — Create credit history component
**File**: `app/Livewire/CreditHistory.php`
- Paginated list of all credit transactions for the authenticated user
- Shows: type, amount (green for earned, red for spent), date, reference link
- Filter by type (all, earned, spent)

**File**: `resources/views/livewire/credit-history.blade.php`
- Dark theme table with pagination
- Type badges with icons
- Amount color-coded (emerald for positive, red for negative)

### Step 8.2 — Add route
**File**: `routes/web.php`
- Add: `Route::get('/credits', CreditHistory::class)->name('credits.history')->middleware(['auth', 'verified']);`

### Step 8.3 — Add nav link
**File**: `resources/views/components/cv-builder-nav.blade.php` or `resources/views/components/desktop-user-menu.blade.php`
- Add "Credit History" link in user menu or navbar

### Step 8.4 — Write tests
**File**: `tests/Feature/Credit/CreditHistoryTest.php`
- Page loads for authenticated users
- Shows transactions in correct order
- Pagination works
- Filter by type works
- Only shows user's own transactions

---

## Phase 9: Polish + Edge Cases

### Step 9.1 — Dispatch credit update events
- In all Livewire components that deduct/add credits, dispatch `credits-updated` event after the operation so the navbar indicator refreshes in real-time

### Step 9.2 — Handle edge cases
- Token usage returning 0: log warning, charge minimum
- User has no CreditBalance row: auto-create on first access
- Race condition: `lockForUpdate()` in CreditManager already handles this
- Builder chat message counting: count from `agent_conversation_messages` table

### Step 9.3 — Run Pint
- `vendor/bin/pint --dirty --format agent`

### Step 9.4 — Full test suite
- Run `php artisan test --compact` to verify all tests pass

---

## Summary

| Phase | Files Created/Modified | Key Deliverable |
|-------|----------------------|-----------------|
| 1. Foundation | 4 migrations, 4 models, 4 factories, 1 config, 1 model update, 4 test files | Database + config + models |
| 2. CreditManager | 1 service, 1 test file | Core credit logic |
| 3. Referrals | 1 service, 1 middleware, 1 registration update, 2 test files | Referral flow + registration hook |
| 4. AI Integration | 4 Livewire updates, 3 test files | Credit checks on all AI calls |
| 5. Monthly Reset | 1 command, 1 route update, 1 test file | Scheduled renewal |
| 6. Balance UI | 2 Livewire components, 3 view updates, 1 test file | Navbar + chat balance display |
| 7. Referral Page | 1 Livewire component, 1 view, 2 route updates, 1 test file | Referral dashboard |
| 8. Credit History | 1 Livewire component, 1 view, 2 route updates, 1 test file | Transaction history |
| 9. Polish | Event dispatching, edge cases, formatting, full test run | Production-ready |

**Total new files**: ~30
**Total modified files**: ~12
**Total test files**: ~14
