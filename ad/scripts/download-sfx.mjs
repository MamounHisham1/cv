import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';

const SFX_DIR = 'public/sfx';

if (!existsSync(SFX_DIR)) {
  mkdirSync(SFX_DIR, { recursive: true });
}

const SOURCES = {
  // Remotion official high-quality recorded sound effects
  'whoosh.wav': 'https://remotion.media/whoosh.wav',
  'swoosh.wav': 'https://remotion.media/whoosh.wav',
  'rising-whoosh.wav': 'https://remotion.media/whoosh.wav',
  'chime.wav': 'https://remotion.media/ding.wav',
  'reveal.wav': 'https://remotion.media/ding.wav',
  'free-badge.wav': 'https://remotion.media/ding.wav',
  'score-reveal.wav': 'https://remotion.media/ding.wav',
  'success.wav': 'https://remotion.media/ding.wav',

  // Kenney CC0 UI Audio - professionally recorded tactile feedback WAVs
  'click.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/click1.wav',
  'digital-press.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch1.wav',
  'pulse.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch3.wav',
  'tick.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch21.wav',
  'pop.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch12.wav',
  'slide-in.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch10.wav',
  'impact.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch5.wav',
  'glitch.wav': 'https://raw.githubusercontent.com/Calinou/kenney-ui-audio/master/addons/kenney_ui_audio/switch33.wav',
};

async function downloadFile(name, url) {
  console.log(`Downloading ${name} from ${url}...`);
  try {
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`Failed to download: ${res.statusText}`);
    }
    const buffer = Buffer.from(await res.arrayBuffer());
    writeFileSync(join(SFX_DIR, name), buffer);
    console.log(`Successfully saved ${name} (${buffer.length} bytes)`);
  } catch (error) {
    console.error(`Error downloading ${name}:`, error.message);
  }
}

async function main() {
  console.log('Downloading real, premium, professionally recorded WAV sound effects...');
  for (const [name, url] of Object.entries(SOURCES)) {
    await downloadFile(name, url);
  }

  // Create an extremely quiet 1-second soft ambient drone for soft-pad.wav
  console.log('Generating soft-pad.wav (ultra-soft physical low drone)...');
  const sampleRate = 44100;
  const duration = 1.0;
  const numSamples = sampleRate * duration;
  const buffer = Buffer.alloc(44 + numSamples * 2);
  
  // Write WAV Header
  buffer.write('RIFF', 0);
  buffer.writeUInt32LE(36 + numSamples * 2, 4);
  buffer.write('WAVE', 8);
  buffer.write('fmt ', 12);
  buffer.writeUInt32LE(16, 16);
  buffer.writeUInt16LE(1, 20);
  buffer.writeUInt16LE(1, 22);
  buffer.writeUInt32LE(sampleRate, 24);
  buffer.writeUInt32LE(sampleRate * 2, 28);
  buffer.writeUInt16LE(2, 32);
  buffer.writeUInt16LE(16, 34);
  buffer.write('data', 36);
  buffer.writeUInt32LE(numSamples * 2, 40);

  // Synthesize extremely low volume pure sub-bass (50Hz) with soft envelope
  for (let i = 0; i < numSamples; i++) {
    const t = i / sampleRate;
    const envelope = Math.sin(Math.PI * t / duration); // soft fade in/out
    const val = Math.sin(2 * Math.PI * 50 * t) * envelope * 0.04; // extremely subtle 4% volume
    const clamped = Math.max(-1, Math.min(1, val));
    buffer.writeInt16LE(Math.round(clamped * 32767), 42 + i * 2);
  }

  writeFileSync(join(SFX_DIR, 'soft-pad.wav'), buffer);
  console.log('Successfully generated soft-pad.wav');

  console.log('Sound effects download and setup complete!');
}

main().catch(console.error);
