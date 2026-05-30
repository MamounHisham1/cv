import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice/options';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const recruiterText = "سيرتك تبرز عملاً بقواعد البيانات. كيف حللت مشكلة حرجة بالخوادم تحت الضغط؟";
const seekerText = "امتلأت قاعدة البيانات في ذروة المرور، فحولتها فوراً للنسخ الاحتياطية.";

const CONFIGS = [
  {
    name: 'saudi',
    recruiterVoice: 'ar-SA-HamedNeural',
    seekerVoice: 'ar-SA-ZariyahNeural',
    recruiterRate: '-12%',
    seekerRate: '-8%',
  },
  {
    name: 'egyptian',
    recruiterVoice: 'ar-EG-ShakirNeural',
    seekerVoice: 'ar-EG-SalmaNeural',
    recruiterRate: '-10%',
    seekerRate: '-6%',
  },
  {
    name: 'emirati',
    recruiterVoice: 'ar-AE-HamdanNeural',
    seekerVoice: 'ar-AE-FatimaNeural',
    recruiterRate: '-12%',
    seekerRate: '-8%',
  }
];

async function generateOption(cfg) {
  console.log(`Generating Option: ${cfg.name.toUpperCase()}...`);
  const ttsRecruiter = new EdgeTTS();
  const ttsSeeker = new EdgeTTS();

  try {
    // Generate Recruiter
    await ttsRecruiter.synthesize(recruiterText, cfg.recruiterVoice, {
      rate: cfg.recruiterRate,
      pitch: '+0Hz'
    });
    const recruiterPath = join(OUT_DIR, `recruiter_${cfg.name}`);
    const finalRecruiterPath = await ttsRecruiter.toFile(recruiterPath);
    console.log(`  - Recruiter saved to: ${finalRecruiterPath}`);

    // Generate Seeker
    await ttsSeeker.synthesize(seekerText, cfg.seekerVoice, {
      rate: cfg.seekerRate,
      pitch: '+0Hz'
    });
    const seekerPath = join(OUT_DIR, `seeker_${cfg.name}`);
    const finalSeekerPath = await ttsSeeker.toFile(seekerPath);
    console.log(`  - Seeker saved to: ${finalSeekerPath}`);

  } catch (error) {
    console.error(`Error generating ${cfg.name}:`, error.message);
  }
}

async function main() {
  for (const cfg of CONFIGS) {
    await generateOption(cfg);
    console.log('------------------------------------');
  }
  console.log('All Arabic voice comparison options generated successfully!');
}

main().catch(console.error);
