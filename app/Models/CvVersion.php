<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvVersion extends Model
{
    protected $fillable = [
        'cv_id',
        'title',
        'template_id',
        'industry_pack',
        'personal_info',
        'summary',
        'section_order',
        'sections',
        'label',
        'fingerprint',
    ];

    protected function casts(): array
    {
        return [
            'personal_info' => 'array',
            'section_order' => 'array',
            'sections' => 'array',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Capture a snapshot of the given CV (scalar fields + all six child
     * sections). Returns the persisted CvVersion.
     */
    public static function capture(Cv $cv, ?string $label = null): self
    {
        $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);

        return self::create([
            'cv_id' => $cv->id,
            'title' => $cv->title,
            'template_id' => $cv->template_id,
            'industry_pack' => $cv->industry_pack,
            'personal_info' => $cv->personal_info,
            'summary' => $cv->summary,
            'section_order' => $cv->section_order,
            'sections' => [
                'experiences' => $cv->experiences->map(fn ($m) => self::stripKeys($m))->all(),
                'educations' => $cv->educations->map(fn ($m) => self::stripKeys($m))->all(),
                'skills' => $cv->skills->map(fn ($m) => self::stripKeys($m))->all(),
                'certifications' => $cv->certifications->map(fn ($m) => self::stripKeys($m))->all(),
                'projects' => $cv->projects->map(fn ($m) => self::stripKeys($m))->all(),
                'languages' => $cv->languages->map(fn ($m) => self::stripKeys($m))->all(),
            ],
            'label' => $label,
        ]);
    }

    /**
     * Restore this snapshot onto the CV: overwrite scalar fields, replace
     * every child section, preserving the CV's own id/user. Operates in a
     * transaction so a partial failure leaves the CV intact.
     */
    public function revert(): Cv
    {
        return \DB::transaction(function () {
            $cv = $this->cv;
            $cv->forceFill([
                'title' => $this->title,
                'template_id' => $this->template_id,
                'industry_pack' => $this->industry_pack,
                'personal_info' => $this->personal_info,
                'summary' => $this->summary,
                'section_order' => $this->section_order,
            ])->save();

            // Wipe current children, then re-create from the snapshot.
            foreach (['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages'] as $relation) {
                $cv->{$relation}()->delete();
                foreach (($this->sections[$relation] ?? []) as $attrs) {
                    // Re-attach via the relation so cv_id is set correctly.
                    $cv->{$relation}()->create($attrs);
                }
            }

            return $cv->fresh();
        });
    }

    /**
     * Reduce a model to a fillable-only attribute array (drop id, cv_id,
     * timestamps) so it can be re-created cleanly on revert.
     */
    private static function stripKeys(Model $model): array
    {
        return collect($model->getAttributes())
            ->except(['id', 'cv_id', 'created_at', 'updated_at'])
            ->toArray();
    }

    /**
     * Capture a snapshot only if the CV's content has changed since the
     * last snapshot — avoids stacking near-identical versions on every
     * export. "Changed" is a hash over scalar fields + child sections.
     */
    public static function snapshotIfChanged(Cv $cv, ?string $label = null): ?self
    {
        $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);

        $fingerprint = md5(serialize([
            'scalar' => [
                'title' => $cv->title,
                'template_id' => $cv->template_id,
                'industry_pack' => $cv->industry_pack,
                'personal_info' => $cv->personal_info,
                'summary' => $cv->summary,
                'section_order' => $cv->section_order,
            ],
            'experiences' => $cv->experiences->map(fn ($m) => self::stripKeys($m))->all(),
            'educations' => $cv->educations->map(fn ($m) => self::stripKeys($m))->all(),
            'skills' => $cv->skills->map(fn ($m) => self::stripKeys($m))->all(),
            'certifications' => $cv->certifications->map(fn ($m) => self::stripKeys($m))->all(),
            'projects' => $cv->projects->map(fn ($m) => self::stripKeys($m))->all(),
            'languages' => $cv->languages->map(fn ($m) => self::stripKeys($m))->all(),
        ]));

        $latest = $cv->versions()->latest()->first();
        if ($latest && hash_equals($latest->fingerprint ?? '', $fingerprint)) {
            return null;
        }

        $version = self::capture($cv, $label);
        $version->fingerprint = $fingerprint;
        $version->saveQuietly();

        return $version->fresh();
    }
}
