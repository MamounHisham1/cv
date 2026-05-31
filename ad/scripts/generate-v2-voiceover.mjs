import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';

const ELEVENLABS_API_KEY = process.env.ELEVENLABS_API_KEY || 'sk_30c9d102ccab3275afe2b2aa671f82a194ef6c6dbac1554f';
const VOICE_ID = 'pNInz6obpgDQGcFmaJgB';
const MODEL_ID = 'eleven_monolingual_v1';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const SCRIPT = `You've sent a hundred CVs this month. How many callbacks?
The problem isn't you. It's the robot reading your CV.
Meet SeratyAI. The first AI career toolkit.
Build an ATS-optimized CV from scratch,
or upload yours and watch AI transform it.
See exactly where you stand.
AI analyzes your CV against real job descriptions.
Then practice with a live AI interviewer.
Real questions. Real feedback.
Our users see 3x more callbacks in just two weeks.
Your first interview is free. Start today.`;

async function main() {
  console.log(`Generating full narration with ElevenLabs (${VOICE_ID})...`);

  try {
    const response = await fetch(
      `https://api.elevenlabs.io/v1/text-to-speech/${VOICE_ID}`,
      {
        method: 'POST',
        headers: {
          'xi-api-key': ELEVENLABS_API_KEY,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          text: SCRIPT,
          model_id: MODEL_ID,
          voice_settings: {
            stability: 0.55,
            similarity_boost: 0.75,
            style: 0.2,
            speaking_rate: 0.85,
          },
        }),
      }
    );

    if (!response.ok) {
      const err = await response.text();
      throw new Error(`ElevenLabs API error: ${response.status} - ${err}`);
    }

    const arrayBuffer = await response.arrayBuffer();
    const buffer = Buffer.from(arrayBuffer);

    const outPath = join(OUT_DIR, 'v2-narration.mp3');
    writeFileSync(outPath, buffer);
    console.log(`Successfully saved narration (${buffer.length} bytes) to ${outPath}`);
  } catch (error) {
    console.error('Error generating voiceover:', error.message);
    process.exit(1);
  }
}

main().catch(console.error);
