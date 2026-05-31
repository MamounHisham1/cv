import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join } from 'path';
import { EdgeTTS } from '@andresaya/edge-tts';

const OUT_DIR = 'public/voice';

if (!existsSync(OUT_DIR)) {
  mkdirSync(OUT_DIR, { recursive: true });
}

const SCENES = [
  {
    name: 'v2ar-scene01',
    text: "ليه مش بتقدم على شغل في أوروبا؟ عشان خايف من إنترفيو الإنجليزي؟",
    voice: 'ar-EG-ShakirNeural',
    rate: '-12%',
  },
  {
    name: 'v2ar-scene02',
    text: "ده اللي واقف في طريقك. الخوف من الإنترفيو الإنجليزي. تخاف تتهته، تخاف تعبر عن نفسك. طبيعي خالص.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-10%',
  },
  {
    name: 'v2ar-scene03',
    text: "عشان كده فيه سيراتي. عشان تساعدك تتحضر وتدخل الإنترفيو وأنت واثق.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-10%',
  },
  {
    name: 'v2ar-scene04',
    text: "سيراتي بيبني لك سي في قوي يناسب أوروبا. بيكتب لك ملخص احترافي ويرتب خبراتك بكلمات مفتاحية تجذب أي نظام.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-8%',
  },
  {
    name: 'v2ar-scene05',
    text: "وبيديك نمرة قد إيه السي في بتاعك مناسب لأنظمة التتبع. لو قليلة، يقول لك تغير إيه عشان توصل لـ 100٪.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-8%',
  },
  {
    name: 'v2ar-scene06',
    text: "وبعد ما السي في يبقى جاهز، نبدأ نتمرن.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-10%',
  },
  {
    name: 'v2ar-recruiter',
    text: "Tell me about a time you solved a difficult problem at work.",
    voice: 'en-US-AndrewNeural',
    rate: '-5%',
  },
  {
    name: 'v2ar-seeker',
    text: "In my previous role, we faced a server issue during peak traffic. I quickly assembled the team, identified the bottleneck, and reduced downtime by 40 percent.",
    voice: 'en-US-JennyNeural',
    rate: '-5%',
  },
  {
    name: 'v2ar-scene07',
    text: "تخيل تتمرن على الإنترفيو من البيت وتاخد تقييم فوري. تعرف تقوي نفسك في إيه قبل المقابلة الحقيقية.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-10%',
  },
  {
    name: 'v2ar-scene08',
    text: "أول إنترفيو مجاني. جرب من غير مخاطرة. ابدأ رحلتك لأوروبا النهارده على سيراتي.",
    voice: 'ar-EG-ShakirNeural',
    rate: '-10%',
  },
];

async function main() {
  console.log('Generating Egyptian Arabic narration with Edge TTS...\n');

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

  console.log('\nAll Arabic voice segments generated successfully!');
}

main().catch(console.error);
