<?php

namespace App\Ai\Tools;

use App\Models\CvSkill;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvSkill implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add a skill to the user\'s CV. Use this when the user mentions a skill they want to add or when you suggest skills the user confirms. You can add one skill at a time.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $name = $request['name'] ?? '';
        $category = $request['category'] ?? 'general';
        $level = $request['level'] ?? 'intermediate';

        if (empty(trim($name))) {
            return 'No skill name provided. Please specify the skill to add.';
        }

        $validCategories = array_keys(CvSkill::CATEGORIES);
        if (! in_array($category, $validCategories)) {
            $category = 'general';
        }

        $validLevels = array_keys(CvSkill::LEVELS);
        if (! in_array($level, $validLevels)) {
            $level = 'intermediate';
        }

        $existing = $this->cv->skills()->where('name', trim($name))->exists();
        if ($existing) {
            return "The skill \"{$name}\" already exists in the CV.";
        }

        $maxSort = $this->cv->skills()->max('sort_order') ?? 0;

        $this->cv->skills()->create([
            'name' => trim($name),
            'category' => $category,
            'level' => $level,
            'sort_order' => $maxSort + 1,
        ]);

        return "Skill \"{$name}\" added to CV under category \"{$category}\" with level \"{$level}\".";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('The skill name (e.g., "Python", "AWS Lambda", "Docker")'),
            'category' => $schema->string()
                ->enum(array_keys(CvSkill::CATEGORIES))
                ->description('Skill category: general, cloud, programming, infrastructure, data, security, soft'),
            'level' => $schema->string()
                ->enum(array_keys(CvSkill::LEVELS))
                ->description('Proficiency level: beginner, intermediate, advanced, expert'),
        ];
    }
}
