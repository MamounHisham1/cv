import React from 'react';
import { AbsoluteFill, Sequence, Audio, staticFile } from 'remotion';
import { ScenarioScene } from './scenes/interviewer/ScenarioScene';
import { SimulatorScene } from './scenes/interviewer/SimulatorScene';
import { CvScanningScene } from './scenes/interviewer/CvScanningScene';
import { ListeningScene } from './scenes/interviewer/ListeningScene';
import { AnalyticsScene } from './scenes/interviewer/AnalyticsScene';
import { CtaScene } from './scenes/cta/CtaScene';

const SCENES = {
  scenario:  { from: 0,    duration: 120 }, // 4s
  simulator: { from: 120,  duration: 120 }, // 4s
  scanning:  { from: 240,  duration: 330 }, // 11s (longer to accommodate recruiter question)
  listening: { from: 570,  duration: 150 }, // 5s (longer to accommodate seeker answer)
  analytics: { from: 720,  duration: 100 }, // 3.33s
  cta:       { from: 820,  duration: 80 },  // 2.67s
};

export const SeratyAd: React.FC = () => {
  return (
    <AbsoluteFill style={{ backgroundColor: '#09090b' }}>
      
      {/* --- VISUAL SECTIONS --- */}
      <Sequence from={SCENES.scenario.from} durationInFrames={SCENES.scenario.duration}>
        <ScenarioScene />
      </Sequence>

      <Sequence from={SCENES.simulator.from} durationInFrames={SCENES.simulator.duration}>
        <SimulatorScene />
      </Sequence>

      <Sequence from={SCENES.scanning.from} durationInFrames={SCENES.scanning.duration}>
        <CvScanningScene />
      </Sequence>

      <Sequence from={SCENES.listening.from} durationInFrames={SCENES.listening.duration}>
        <ListeningScene />
      </Sequence>

      <Sequence from={SCENES.analytics.from} durationInFrames={SCENES.analytics.duration}>
        <AnalyticsScene />
      </Sequence>

      <Sequence from={SCENES.cta.from} durationInFrames={SCENES.cta.duration}>
        <CtaScene />
      </Sequence>

      {/* --- PREMIUM SFX TIMINGS (REAL RECORDED WAVs) --- */}
      
      {/* Scene 1: Scenario (0 - 120) */}
      <Sequence from={0} durationInFrames={30}>
        <Audio src={staticFile('sfx/soft-pad.wav')} volume={0.25} />
      </Sequence>
      <Sequence from={60} durationInFrames={20}>
        <Audio src={staticFile('sfx/impact.wav')} volume={0.4} /> {/* Thump on heartbeat pulse */}
      </Sequence>

      {/* Scene 2: Simulator (120 - 240) */}
      <Sequence from={118} durationInFrames={15}>
        <Audio src={staticFile('sfx/whoosh.wav')} volume={0.35} /> {/* Transition */}
      </Sequence>
      <Sequence from={130} durationInFrames={30}>
        <Audio src={staticFile('sfx/chime.wav')} volume={0.3} /> {/* Neon orb reveal */}
      </Sequence>

      {/* Scene 3: CV Scanning & Speech Box (240 - 570) */}
      <Sequence from={238} durationInFrames={15}>
        <Audio src={staticFile('sfx/rising-whoosh.wav')} volume={0.4} /> {/* Transition */}
      </Sequence>
      <Sequence from={250} durationInFrames={15}>
        <Audio src={staticFile('sfx/digital-press.wav')} volume={0.3} /> {/* CV floats in */}
      </Sequence>
      <Sequence from={280} durationInFrames={20}>
        <Audio src={staticFile('sfx/glitch.wav')} volume={0.25} /> {/* Scanning sweep */}
      </Sequence>
      <Sequence from={335} durationInFrames={30}>
        <Audio src={staticFile('sfx/success.wav')} volume={0.35} /> {/* Recruiter question pop */}
      </Sequence>
      
      {/* --- AI RECRUITER SPOKEN QUESTION --- */}
      <Sequence from={335} durationInFrames={233}>
        <Audio src={staticFile('voice/recruiter-question.mp3')} volume={1.0} />
      </Sequence>

      {/* Scene 4: Spoken Voice Answering (570 - 720) */}
      <Sequence from={568} durationInFrames={15}>
        <Audio src={staticFile('sfx/swoosh.wav')} volume={0.3} /> {/* Transition */}
      </Sequence>
      <Sequence from={590} durationInFrames={20}>
        <Audio src={staticFile('sfx/pulse.wav')} volume={0.2} /> {/* Voice active pulse */}
      </Sequence>
      
      {/* --- JOB SEEKER SPOKEN ANSWER --- */}
      <Sequence from={575} durationInFrames={166}>
        <Audio src={staticFile('voice/seeker-answer.mp3')} volume={1.0} />
      </Sequence>

      {/* Scene 5: Analytics & Insights (720 - 820) */}
      <Sequence from={718} durationInFrames={15}>
        <Audio src={staticFile('sfx/rising-whoosh.wav')} volume={0.4} /> {/* Transition */}
      </Sequence>
      <Sequence from={730} durationInFrames={40}>
        <Audio src={staticFile('sfx/score-reveal.wav')} volume={0.45} /> {/* Circular countdown */}
      </Sequence>
      <Sequence from={800} durationInFrames={30}>
        <Audio src={staticFile('sfx/reveal.wav')} volume={0.3} /> {/* Insights reveal chime */}
      </Sequence>

      {/* Scene 6: Free CTA (820 - 900) */}
      <Sequence from={818} durationInFrames={15}>
        <Audio src={staticFile('sfx/whoosh.wav')} volume={0.35} /> {/* Final transition */}
      </Sequence>
      <Sequence from={845} durationInFrames={30}>
        <Audio src={staticFile('sfx/free-badge.wav')} volume={0.4} /> {/* Glowing free badge chime */}
      </Sequence>

    </AbsoluteFill>
  );
};
