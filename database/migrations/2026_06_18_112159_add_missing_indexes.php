<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add the indexes that were missing from the original schema. The runtime DB
 * is SQLite, which does NOT auto-index foreign-key columns — so every
 * `where('cv_id', ...)` and `orderBy('sort_order')` was a full table scan.
 *
 * Each index is guarded by hasIndex() so the migration is idempotent and
 * safe to re-run on databases that already added the index implicitly.
 */
return new class extends Migration
{
    private function ensureIndex(string $table, string $column, ?string $name = null): void
    {
        $name ??= "{$table}_{$column}_index";

        if (! Schema::hasIndex($table, $name)) {
            Schema::table($table, function (Blueprint $t) use ($column, $name) {
                $t->index($column, $name);
            });
        }
    }

    public function up(): void
    {
        // CV -> section FKs (every relation is filtered by cv_id).
        $this->ensureIndex('cv_experiences', 'cv_id');
        $this->ensureIndex('cv_educations', 'cv_id');
        $this->ensureIndex('cv_skills', 'cv_id');
        $this->ensureIndex('cv_certifications', 'cv_id');
        $this->ensureIndex('cv_projects', 'cv_id');
        $this->ensureIndex('cv_languages', 'cv_id');

        // sort_order is the default orderBy for every section relation.
        $this->ensureIndex('cv_experiences', 'sort_order');
        $this->ensureIndex('cv_educations', 'sort_order');
        $this->ensureIndex('cv_skills', 'sort_order');
        $this->ensureIndex('cv_certifications', 'sort_order');
        $this->ensureIndex('cv_projects', 'sort_order');
        $this->ensureIndex('cv_languages', 'sort_order');

        // CV ownership + dashboard filters.
        $this->ensureIndex('cvs', 'user_id');
        $this->ensureIndex('cvs', 'status');
        $this->ensureIndex('cvs', 'template_id');
        $this->ensureIndex('cvs', 'created_at');

        // Evaluation lookups (CvEvaluator filters heavily on these).
        $this->ensureIndex('cv_evaluations', 'cv_id');
        $this->ensureIndex('cv_evaluations', 'status');

        // Payments + interview dashboards.
        $this->ensureIndex('vfcash_payments', 'user_id');
        $this->ensureIndex('interview_sessions', 'cv_id');
    }

    public function down(): void
    {
        foreach ([
            ['cv_experiences', 'cv_id'], ['cv_educations', 'cv_id'], ['cv_skills', 'cv_id'],
            ['cv_certifications', 'cv_id'], ['cv_projects', 'cv_id'], ['cv_languages', 'cv_id'],
            ['cv_experiences', 'sort_order'], ['cv_educations', 'sort_order'], ['cv_skills', 'sort_order'],
            ['cv_certifications', 'sort_order'], ['cv_projects', 'sort_order'], ['cv_languages', 'sort_order'],
            ['cvs', 'user_id'], ['cvs', 'status'], ['cvs', 'template_id'], ['cvs', 'created_at'],
            ['cv_evaluations', 'cv_id'], ['cv_evaluations', 'status'],
            ['vfcash_payments', 'user_id'], ['interview_sessions', 'cv_id'],
        ] as [$table, $column]) {
            if (Schema::hasIndex($table, "{$table}_{$column}_index")) {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex("{$table}_{$column}_index"));
            }
        }
    }
};
