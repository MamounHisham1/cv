<?php

namespace App\Ai\Tools;

use App\Models\CvSkill;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvSkill implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing skill on the user\'s CV (its name, category, or proficiency level). Always call read_cv_data first to get the skill id, then pass only the fields you want to change.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No skill id provided. Call read_cv_data first to find the skill id.';
        }

        $skill = $this->cv->skills()->find($id);
        if (! $skill) {
            return "No skill found with id {$id} on this CV.";
        }

        $updates = [];

        if (isset($request['name']) && trim((string) $request['name']) !== '') {
            $updates['name'] = trim((string) $request['name']);
        }

        if (isset($request['category'])) {
            $category = $request['category'];
            $updates['category'] = in_array($category, array_keys(CvSkill::CATEGORIES), true) ? $category : 'general';
        }

        if (isset($request['level'])) {
            $level = $request['level'];
            $updates['level'] = in_array($level, array_keys(CvSkill::LEVELS), true) ? $level : 'intermediate';
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        $skill->update($updates);

        return "Skill \"{$skill->name}\" updated. Changed: ".implode(', ', array_keys($updates)).'.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the skill to update (from read_cv_data)'),
            'name' => $schema->string()->description('New skill name'),
            'category' => $schema->string()
                ->enum(array_keys(CvSkill::CATEGORIES))
                ->description('Skill category: general, cloud, programming, infrastructure, data, security, soft'),
            'level' => $schema->string()
                ->enum(array_keys(CvSkill::LEVELS))
                ->description('Proficiency level: beginner, intermediate, advanced, expert'),
        ];
    }
}
