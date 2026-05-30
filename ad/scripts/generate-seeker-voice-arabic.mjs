import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

// Shorter Arabic Candidate response
const text = "امتلأت قاعدة البيانات في ذروة المرور، فحولتها فوراً للنسخ الاحتياطية.";

async function main() {
  console.log(`Generating Job Seeker Arabic voice answer using Microsoft Edge TTS (ar-SA-ZariyahNeural)...`);
  
  try {
    const tts = new EdgeTTS();
    
    // ar-SA-ZariyahNeural is a very natural and energetic Saudi female voice
    await tts.synthesize(text, 'ar-SA-ZariyahNeural', {
      rate: '+5%', // energetic and natural tempo
      pitch: '+0Hz'
    });

    const path = join(OUT_DIR, 'seeker-answer-arabic');
    const finalPath = await tts.toFile(path);
    console.log(`Successfully generated seeker Arabic voice and saved to ${finalPath}`);
  } catch (error) {
    console.error('Error generating voiceover:', error.message);
  }
}

main().catch(console.error);
