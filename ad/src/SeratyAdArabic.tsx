import React from 'react';
import { AbsoluteFill, Sequence, Audio, staticFile } from 'remotion';
import { ScenarioSceneArabic } from './scenes_arabic/interviewer/ScenarioSceneArabic';
import { SimulatorSceneArabic } from './scenes_arabic/interviewer/SimulatorSceneArabic';
import { CvScanningSceneArabic } from './scenes_arabic/interviewer/CvScanningSceneArabic';
import { ListeningSceneArabic } from './scenes_arabic/interviewer/ListeningSceneArabic';
import { AnalyticsSceneArabic } from './scenes_arabic/interviewer/AnalyticsSceneArabic';
import { CtaSceneArabic } from './scenes_arabic/cta/CtaSceneArabic';

const SCENES = {
  scenario:  { from: 0,    duration: 90 },  // 3s (punchy mobile intro hook)
  simulator: { from: 90,   duration: 90 },  // 3s (breathing voice orb)
  scanning:  { from: 180,  duration: 320 }, // 10.67s (CV scan & AI Recruiter speech)
  listening: { from: 500,  duration: 240 }, // 8s (User spoken answer)
  analytics: { from: 740,  duration: 80 },  // 2.67s (Instant score rings countdown)
  cta:       { from: 820,  duration: 80 },  // 2.67s (Final action & website URL)
};

export const SeratyAdArabic: React.FC = () => {
  return (
    <AbsoluteFill style={{ backgroundColor: '#09090b' }}>
      
      {/* --- VISUAL SECTIONS (ARABIC LOCALE) --- */}
      <Sequence from={SCENES.scenario.from} durationInFrames={SCENES.scenario.duration}>
        <ScenarioSceneArabic />
      </Sequence>

      <Sequence from={SCENES.simulator.from} durationInFrames={SCENES.simulator.duration}>
        <SimulatorSceneArabic />
      </Sequence>

      <Sequence from={SCENES.scanning.from} durationInFrames={SCENES.scanning.duration}>
        <CvScanningSceneArabic />
      </Sequence>

      <Sequence from={SCENES.listening.from} durationInFrames={SCENES.listening.duration}>
        <ListeningSceneArabic />
      </Sequence>

      <Sequence from={SCENES.analytics.from} durationInFrames={SCENES.analytics.duration}>
        <AnalyticsSceneArabic />
      </Sequence>

      <Sequence from={SCENES.cta.from} durationInFrames={SCENES.cta.duration}>
        <CtaSceneArabic />
      </Sequence>

      {/* --- PREMIUM SFX TIMINGS & ELEVENLABS DIALOGUE TIMINGS --- */}
      
      {/* Scene 1: Scenario (0 - 90) */}
      <Sequence from={0} durationInFrames={30}>
        <Audio src={staticFile('sfx/soft-pad.wav')} volume={0.25} />
      </Sequence>
      <Sequence from={50} durationInFrames={20}>
        <Audio src={staticFile('sfx/impact.wav')} volume={0.4} /> {/* Pulse thump */}
      </Sequence>

      {/* Scene 2: Simulator (90 - 180) */}
      <Sequence from={88} durationInFrames={15}>
        <Audio src={staticFile('sfx/whoosh.wav')} volume={0.35} /> {/* Transition */}
      </Sequence>
      <Sequence from={100} durationInFrames={30}>
        <Audio src={staticFile('sfx/chime.wav')} volume={0.3} /> {/* Central orb reveal */}
      </Sequence>

      {/* Scene 3: CV Scanning & Speech Box (180 - 500) */}
      <Sequence from={178} durationInFrames={15}>
        <Audio src={staticFile('sfx/rising-whoosh.wav')} volume={0.4} /> {/* Transition */}
      </Sequence>
      <Sequence from={190} durationInFrames={15}>
        <Audio src={staticFile('sfx/digital-press.wav')} volume={0.3} /> {/* CV floats in */}
      </Sequence>
      <Sequence from={220} durationInFrames={20}>
        <Audio src={staticFile('sfx/glitch.wav')} volume={0.25} /> {/* Laser scanning sweep */}
      </Sequence>
      <Sequence from={275} durationInFrames={30}>
        <Audio src={staticFile('sfx/success.wav')} volume={0.35} /> {/* Speech card pops */}
      </Sequence>
      
      {/* 🗣️ AI RECRUITER ELEVENLABS COLLOQUIAL ARABIC VOICE */}
      <Sequence from={275} durationInFrames={202}>
        <Audio src={staticFile('voice/question.wav')} volume={1.0} />
      </Sequence>

      {/* Scene 4: Spoken Voice Answering (500 - 740) */}
      <Sequence from={498} durationInFrames={15}>
        <Audio src={staticFile('sfx/swoosh.wav')} volume={0.3} /> {/* Transition */}
      </Sequence>
      <Sequence from={520} durationInFrames={20}>
        <Audio src={staticFile('sfx/pulse.wav')} volume={0.2} /> {/* Audio ripple click */}
      </Sequence>
      
      {/* 🗣️ JOB SEEKER ELEVENLABS COLLOQUIAL ARABIC VOICE */}
      <Sequence from={505} durationInFrames={223}>
        <Audio src={staticFile('voice/answer.wav')} volume={1.0} />
      </Sequence>

      {/* Scene 5: Analytics & Insights (740 - 820) */}
      <Sequence from={738} durationInFrames={15}>
        <Audio src={staticFile('sfx/rising-whoosh.wav')} volume={0.4} /> {/* Transition */}
      </Sequence>
      <Sequence from={750} durationInFrames={40}>
        <Audio src={staticFile('sfx/score-reveal.wav')} volume={0.45} /> {/* Metric Rings reveal chimes */}
      </Sequence>
      <Sequence from={800} durationInFrames={30}>
        <Audio src={staticFile('sfx/reveal.wav')} volume={0.3} /> {/* Insight cards reveal chime */}
      </Sequence>

      {/* Scene 6: Free CTA (820 - 900) */}
      <Sequence from={818} durationInFrames={15}>
        <Audio src={staticFile('sfx/whoosh.wav')} volume={0.35} /> {/* Final transition */}
      </Sequence>
      <Sequence from={840} durationInFrames={30}>
        <Audio src={staticFile('sfx/free-badge.wav')} volume={0.4} /> {/* Free badge slide chime */}
      </Sequence>

    </AbsoluteFill>
  );
};
