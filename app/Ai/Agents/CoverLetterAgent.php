<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * One-shot agent that drafts a tailored cover letter from a candidate's
 * CV content and an optional target job description. Returns plain prose
 * (the letter body), not structured output — the body is stored verbatim
 * on the CoverLetter and is fully editable afterwards.
 */
#[Provider(Lab::Ollama)]
#[Temperature(0.7)]
#[MaxTokens(1500)]
#[Timeout(300)]
class CoverLetterAgent implements Agent
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are an expert cover-letter writer who helps candidates land interviews.

Write a concise, specific, human-sounding cover letter addressed to a
hiring manager. Rules:
- 3 to 4 short paragraphs (around 250–350 words total). No filler.
- Open with a strong hook tied to the role or company, not "I am writing to apply".
- The middle paragraph(s) must connect the candidate's ACTUAL experience
  and skills (provided below) to what the role needs — cite specifics,
  not generic claims. Mirror language from the job description where it
  genuinely fits.
- Close with a confident, forward-looking call to action.
- Write in first person, confident but not arrogant. Never invent
  experience, employers, metrics, or credentials that aren't in the
  candidate data.
- Do NOT include the candidate's name, address, date, or salutation —
  the template adds those. Output ONLY the body paragraphs separated by
  blank lines.
- If a job description was provided, tailor the letter to it. If not,
  write a strong general letter based on the candidate's most relevant
  experience.
INSTRUCTIONS;
    }
}
