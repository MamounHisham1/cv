import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

// Shorter Arabic Recruiter question
const text = "سيرتك تبرز عملاً بقواعد البيانات. كيف حللت مشكلة حرجة بالخوادم تحت الضغط؟";

async function main() {
  console.log(`Generating AI Recruiter Arabic voice question using Microsoft Edge TTS (ar-SA-HamedNeural)...`);
  
  try {
    const tts = new EdgeTTS();
    
    // ar-SA-HamedNeural is a highly professional, natural Gulf Arabic male voice
    await tts.synthesize(text, 'ar-SA-HamedNeural', {
      rate: '-12%', // slowed down for extreme human-like warmth
      pitch: '+0Hz'
    });

    const path = join(OUT_DIR, 'recruiter-question-arabic');
    const finalPath = await tts.toFile(path);
    console.log(`Successfully generated recruiter Arabic voice and saved to ${finalPath}`);
  } catch (error) {
    console.error('Error generating voiceover:', error.message);
  }
}

main().catch(console.error);
