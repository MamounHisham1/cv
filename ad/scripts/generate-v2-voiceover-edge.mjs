import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const SCENES = [
  {
    name: 'v2-scene01',
    text: "You've sent a hundred CVs this month. How many callbacks?",
    voice: 'en-US-AndrewNeural',
    rate: '-20%',
  },
  {
    name: 'v2-scene02',
    text: "The problem isn't you. It's the robot reading your CV. Before a human even sees it, an AI has already rejected you.",
    voice: 'en-US-AndrewNeural',
    rate: '-15%',
  },
  {
    name: 'v2-scene03',
    text: 'Meet SeratyAI. The first AI-powered career toolkit designed to get you hired.',
    voice: 'en-US-AndrewNeural',
    rate: '-15%',
  },
  {
    name: 'v2-scene04',
    text: 'Build an ATS-optimized CV from scratch in minutes. Or upload yours and watch AI transform it with smart suggestions and modern templates.',
    voice: 'en-US-AndrewNeural',
    rate: '-12%',
  },
  {
    name: 'v2-scene05',
    text: 'See exactly where you stand. AI analyzes your CV against real job descriptions and shows you exactly how to improve, step by step.',
    voice: 'en-US-AndrewNeural',
    rate: '-12%',
  },
  {
    name: 'v2-scene06',
    text: 'Then practice with a live AI interviewer. Watch as it asks you a real question, hear how a great response sounds, and get instant feedback.',
    voice: 'en-US-AndrewNeural',
    rate: '-15%',
  },
  {
    name: 'v2-scene07',
    text: 'Our users see three times more callbacks in just two weeks. Real results from real people.',
    voice: 'en-US-AndrewNeural',
    rate: '-15%',
  },
  {
    name: 'v2-scene08',
    text: 'Your first interview is completely free. No credit card required. Start building your career today.',
    voice: 'en-US-AndrewNeural',
    rate: '-15%',
  },
  {
    name: 'v2-recruiter',
    text: 'Tell me about a time you led a team through a difficult project. What was your approach and what was the outcome?',
    voice: 'en-US-AndrewNeural',
    rate: '-5%',
  },
  {
    name: 'v2-seeker',
    text: 'I led a team of five designers during a major product launch. We were behind schedule, so I reorganised our workflow, prioritised critical features, and we delivered on time with a twenty percent increase in user engagement.',
    voice: 'en-US-JennyNeural',
    rate: '-5%',
  },
];

async function main() {
  console.log('Generating per-scene narration with Edge TTS...\n');

  for (const scene of SCENES) {
    console.log(`  Generating ${scene.name} (${scene.voice}, rate: ${scene.rate})...`);

    try {
      const tts = new EdgeTTS();
      await tts.synthesize(scene.text, scene.voice, {
        rate: scene.rate,
        pitch: '+0Hz',
      });

      const path = join(OUT_DIR, scene.name);
      const finalPath = await tts.toFile(path);
      console.log(`  ✓ Saved ${scene.name}`);
    } catch (error) {
      console.error(`  ✗ Error generating ${scene.name}:`, error.message);
    }
  }

  console.log('\nAll voice segments generated successfully!');
}

main().catch(console.error);
