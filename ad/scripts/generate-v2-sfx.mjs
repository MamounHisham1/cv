import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';

const ELEVENLABS_API_KEY = process.env.ELEVENLABS_API_KEY || 'sk_30c9d102ccab3275afe2b2aa671f82a194ef6c6dbac1554f';

const OUT_DIR = 'public/sfx/v2';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const SOUNDS = [
  {
    name: 'hook-deflate.mp3',
    text: 'A soft, subtle deflating sound, like air slowly leaving a balloon, cinematic and sad, gentle',
    duration_seconds: 1.5,
  },
  {
    name: 'scan-digital.mp3',
    text: 'A subtle digital scanning sound effect, like a photocopier or barcode scanner, quiet and mechanical',
    duration_seconds: 2.0,
  },
  {
    name: 'scan-reject.mp3',
    text: 'A soft rejection sound, like a gentle buzzer or a stamp on paper, subtle and professional',
    duration_seconds: 1.5,
  },
  {
    name: 'brand-chime.mp3',
    text: 'A warm, elegant chime or logo reveal sound, premium and modern, like an Apple keynote',
    duration_seconds: 2.0,
  },
  {
    name: 'typing-subtle.mp3',
    text: 'Subtle keyboard typing sounds, soft clicks, professional office environment',
    duration_seconds: 2.0,
  },
  {
    name: 'score-rising.mp3',
    text: 'A slow rising synth tone that builds anticipation, cinematic and inspiring, crescendo',
    duration_seconds: 3.0,
  },
  {
    name: 'score-complete.mp3',
    text: 'A satisfying completion chime, like a level up or achievement, warm and pleasant',
    duration_seconds: 2.0,
  },
  {
    name: 'notification-pop.mp3',
    text: 'A soft notification pop sound, like an iPhone notification, subtle and clean',
    duration_seconds: 1.0,
  },
  {
    name: 'success-chord.mp3',
    text: 'A warm resonant success chord, emotional and triumphant but subtle, cinematic',
    duration_seconds: 2.5,
  },
  {
    name: 'button-click.mp3',
    text: 'A premium UI button click, soft haptic feedback, like pressing a high-end mechanical switch',
    duration_seconds: 0.8,
  },
];

async function generateSound({ name, text, duration_seconds }) {
  console.log(`Generating ${name}...`);

  try {
    const response = await fetch('https://api.elevenlabs.io/v1/sound-generation', {
      method: 'POST',
      headers: {
        'xi-api-key': ELEVENLABS_API_KEY,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        text,
        duration_seconds,
      }),
    });

    if (!response.ok) {
      const err = await response.text();
      console.error(`  ✗ Error generating ${name}: ElevenLabs SFX error ${response.status} - ${err}`);
      return;
    }

    const arrayBuffer = await response.arrayBuffer();
    const buffer = Buffer.from(arrayBuffer);

    const outPath = join(OUT_DIR, name);
    writeFileSync(outPath, buffer);
    console.log(`  ✓ Saved ${name} (${buffer.length} bytes)`);
  } catch (error) {
    console.error(`  ✗ Error generating ${name}:`, error.message);
  }
}

async function main() {
  console.log('Generating custom cinematic sound effects via ElevenLabs...\n');

  for (const sound of SOUNDS) {
    await generateSound(sound);
    await new Promise((r) => setTimeout(r, 600));
  }

  console.log('\nSound effects generation complete!');
}

main().catch(console.error);
