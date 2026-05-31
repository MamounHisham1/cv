import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';

const OUT_DIR = 'public/sfx/v2';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const SAMPLE_RATE = 44100;

function writeWav(filePath, samples) {
  const numSamples = samples.length;
  const buffer = Buffer.alloc(44 + numSamples * 2);

  buffer.write('RIFF', 0);
  buffer.writeUInt32LE(36 + numSamples * 2, 4);
  buffer.write('WAVE', 8);
  buffer.write('fmt ', 12);
  buffer.writeUInt32LE(16, 16);
  buffer.writeUInt16LE(1, 20);
  buffer.writeUInt16LE(1, 22);
  buffer.writeUInt32LE(SAMPLE_RATE, 24);
  buffer.writeUInt32LE(SAMPLE_RATE * 2, 28);
  buffer.writeUInt16LE(2, 32);
  buffer.writeUInt16LE(16, 34);
  buffer.write('data', 36);
  buffer.writeUInt32LE(numSamples * 2, 40);

  for (let i = 0; i < numSamples; i++) {
    const clamped = Math.max(-1, Math.min(1, samples[i]));
    buffer.writeInt16LE(Math.round(clamped * 32767), 42 + i * 2);
  }

  writeFileSync(filePath, buffer);
}

function envelope(i, total, attack = 0.05, release = 0.15) {
  const a = Math.min(1, i / (total * attack));
  const r = i > total * (1 - release) ? 1 - (i - total * (1 - release)) / (total * release) : 1;
  return Math.min(a, r);
}

function generateThud(duration = 1.5) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.01, 0.4);
    const sub = Math.sin(2 * Math.PI * 40 * t) * 0.6;
    const mid = Math.sin(2 * Math.PI * 80 * t) * 0.2;
    const noise = (Math.random() * 2 - 1) * 0.05 * Math.max(0, 1 - t * 3);
    samples[i] = (sub + mid + noise) * env * 0.5;
  }
  return samples;
}

function generateSweep(duration = 2.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.02, 0.3);
    const freq = 200 + (t / duration) * 1800;
    const val = Math.sin(2 * Math.PI * freq * t);
    const noise = (Math.random() * 2 - 1) * 0.02;
    samples[i] = (val + noise) * env * 0.3;
  }
  return samples;
}

function generateReject(duration = 1.5) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.001, 0.5);
    const f1 = Math.sin(2 * Math.PI * 100 * t) * 0.4;
    const f2 = Math.sin(2 * Math.PI * 60 * t) * 0.3;
    const noise = (Math.random() * 2 - 1) * 0.3 * Math.max(0, 1 - t * 3);
    samples[i] = (f1 + f2 + noise) * env * 0.4;
  }
  return samples;
}

function generateChime(duration = 2.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  const harmonics = [1, 2.5, 4.2, 6.8];
  const amplitudes = [0.5, 0.2, 0.1, 0.04];
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.01, 0.5);
    let val = 0;
    for (let h = 0; h < harmonics.length; h++) {
      val += Math.sin(2 * Math.PI * 440 * harmonics[h] * t) * amplitudes[h] * Math.exp(-t * 1.5);
    }
    samples[i] = val * env * 0.4;
  }
  return samples;
}

function generateTyping(duration = 2.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  const clicks = [0.05, 0.25, 0.45, 0.65, 0.85];
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    let val = 0;
    for (const clickTime of clicks) {
      const dt = t - clickTime;
      if (dt > 0 && dt < 0.04) {
        const clickEnv = 1 - dt / 0.04;
        const clickVal = Math.sin(2 * Math.PI * 3000 * dt) * 0.4;
        val += clickVal * clickEnv;
      }
    }
    samples[i] = val * 0.2;
  }
  return samples;
}

function generateRising(duration = 3.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.05, 0.2);
    const freq = 150 + (t / duration) * 1200;
    const val = Math.sin(2 * Math.PI * freq * t);
    const sub = Math.sin(2 * Math.PI * freq * 0.5 * t) * 0.2;
    samples[i] = (val + sub) * env * 0.25;
  }
  return samples;
}

function generateComplete(duration = 2.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  const harmony = [523.25, 659.25, 783.99];
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.01, 0.4);
    let val = 0;
    for (const freq of harmony) {
      val += Math.sin(2 * Math.PI * freq * t) * (0.3 * Math.exp(-t * 0.8));
    }
    const sparkle = Math.sin(2 * Math.PI * 1200 * t) * 0.05 * Math.min(1, t * 10) * Math.exp(-t * 2);
    samples[i] = (val + sparkle) * env * 0.35;
  }
  return samples;
}

function generateNotification(duration = 1.0) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const freq = 800 + Math.sin(t * 30) * 100;
    const env = envelope(i, len, 0.001, 0.7);
    const val = Math.sin(2 * Math.PI * freq * t) * 0.5;
    const sub = Math.sin(2 * Math.PI * 400 * t) * 0.2;
    samples[i] = (val + sub) * env * 0.25;
  }
  return samples;
}

function generateSuccessChord(duration = 2.5) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  const chord = [261.63, 329.63, 392.0, 523.25];
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.02, 0.5);
    let val = 0;
    for (const freq of chord) {
      val += Math.sin(2 * Math.PI * freq * t) * 0.2 * Math.exp(-t * 0.4);
    }
    const shimmer = Math.sin(2 * Math.PI * 2000 * t) * 0.03 * Math.exp(-t * 1.5);
    const bass = Math.sin(2 * Math.PI * 65 * t) * 0.3;
    samples[i] = (val + shimmer + bass) * env * 0.3;
  }
  return samples;
}

function generateButtonClick(duration = 0.8) {
  const len = SAMPLE_RATE * duration;
  const samples = new Float32Array(len);
  for (let i = 0; i < len; i++) {
    const t = i / SAMPLE_RATE;
    const env = envelope(i, len, 0.001, 0.8);
    const thud = Math.sin(2 * Math.PI * 100 * t) * 0.5 * Math.exp(-t * 20);
    const click = Math.sin(2 * Math.PI * 3000 * t) * 0.3 * Math.exp(-t * 40);
    samples[i] = (thud + click) * env * 0.15;
  }
  return samples;
}

async function main() {
  const sounds = [
    { name: 'hook-deflate.mp3', gen: generateThud },
    { name: 'scan-digital.mp3', gen: generateSweep },
    { name: 'scan-reject.mp3', gen: generateReject },
    { name: 'brand-chime.mp3', gen: generateChime },
    { name: 'typing-subtle.mp3', gen: generateTyping },
    { name: 'score-rising.mp3', gen: generateRising },
    { name: 'score-complete.mp3', gen: generateComplete },
    { name: 'notification-pop.mp3', gen: generateNotification },
    { name: 'success-chord.mp3', gen: generateSuccessChord },
    { name: 'button-click.mp3', gen: generateButtonClick },
  ];

  console.log('Generating cinematic sound effects programmatically...\n');

  for (const s of sounds) {
    const samples = s.gen();
    const outPath = join(OUT_DIR, s.name);
    writeWav(outPath, samples);
    console.log(`  ✓ Generated ${s.name}`);
  }

  console.log('\nAll sound effects generated successfully!');
  console.log(`Output directory: ${OUT_DIR}`);
}

main().catch(console.error);
