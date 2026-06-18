<?php

namespace App\Ai\Tools\Telegram;

use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\VfcashPayment;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetRevenueStats implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get revenue and credit statistics: total credit transactions by type, total vfcash payments confirmed (revenue), plan distribution among users, total credits granted.';
    }

    public function handle(Request $request): Stringable|string
    {
        $transactionsByType = CreditTransaction::select('type', DB::raw('count(*) as count'), DB::raw('coalesce(sum(amount), 0) as total'))
            ->groupBy('type')
            ->get();

        $confirmedPayments = VfcashPayment::where('status', 'confirmed')->count();
        $totalRevenue = (float) (VfcashPayment::where('status', 'confirmed')->sum('amount_egp') ?? 0);
        $totalCreditsGranted = (int) (VfcashPayment::where('status', 'confirmed')->sum('credits_granted') ?? 0);

        $planDistribution = CreditBalance::select('plan', DB::raw('count(*) as count'))
            ->groupBy('plan')
            ->pluck('count', 'plan');

        $output = "=== Revenue & Credit Statistics ===\n\n";
        $output .= "Confirmed vfcash payments: {$confirmedPayments}\n";
        $output .= 'Total revenue (confirmed): '.number_format($totalRevenue, 2)." EGP\n";
        $output .= "Total credits granted (confirmed payments): {$totalCreditsGranted}\n\n";

        $output .= "Credit transactions by type:\n";
        if ($transactionsByType->isEmpty()) {
            $output .= "- No credit transactions yet.\n";
        } else {
            foreach ($transactionsByType as $row) {
                $label = $row->type ?: '(none)';
                $output .= "- {$label}: {$row->count} transactions, net ".(int) $row->total." credits\n";
            }
        }

        $output .= "\nUser plan distribution:\n";
        if ($planDistribution->isEmpty()) {
            $output .= "- No credit balances recorded.\n";
        } else {
            foreach ($planDistribution as $plan => $count) {
                $label = $plan ?: '(none)';
                $output .= "- {$label}: {$count} users\n";
            }
        }

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
