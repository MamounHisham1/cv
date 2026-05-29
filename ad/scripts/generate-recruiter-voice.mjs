import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const text = "Your CV highlights major work with scaled databases. Tell me about a time you resolved a critical production failure under pressure.";

async function main() {
  console.log(`Generating AI Recruiter voice question using Microsoft Edge TTS (en-US-AndrewNeural)...`);
  
  try {
    const tts = new EdgeTTS();
    
    // en-US-AndrewNeural is a very natural and warm US male voice
    await tts.synthesize(text, 'en-US-AndrewNeural', {
      rate: '+0%', // standard pace
      pitch: '+0Hz'
    });

    const path = join(OUT_DIR, 'recruiter-question');
    const finalPath = await tts.toFile(path);
    console.log(`Successfully generated recruiter voice and saved to ${finalPath}`);
  } catch (error) {
    console.error('Error generating voiceover:', error.message);
  }
}

main().catch(console.error);

