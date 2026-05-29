import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

// A highly compact, punchy 4-second STAR method snippet
const text = "Our database saturated during a surge, so I immediately redirected traffic to replicas.";

async function main() {
  console.log(`Generating Job Seeker voice answer using Microsoft Edge TTS (en-US-EmmaNeural)...`);
  
  try {
    const tts = new EdgeTTS();
    
    // en-US-EmmaNeural is a very bright, friendly, and professional US female voice
    await tts.synthesize(text, 'en-US-EmmaNeural', {
      rate: '+5%', // slightly faster and energetic pace
      pitch: '+0Hz'
    });

    const path = join(OUT_DIR, 'seeker-answer');
    const finalPath = await tts.toFile(path);
    console.log(`Successfully generated seeker voice and saved to ${finalPath}`);
  } catch (error) {
    console.error('Error generating voiceover:', error.message);
  }
}

main().catch(console.error);
