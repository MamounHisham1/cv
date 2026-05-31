import React from 'react';
import { AbsoluteFill, Sequence, Audio, staticFile } from 'remotion';
import { HookScene } from './scenes_v2/hook/HookScene';
import { AtsRejectionScene } from './scenes_v2/problem/AtsRejectionScene';
import { BrandIntroScene } from './scenes_v2/solution/BrandIntroScene';
import { CvBuilderScene } from './scenes_v2/features/CvBuilderScene';
import { AtsScoreScene } from './scenes_v2/features/AtsScoreScene';
import { InterviewScene } from './scenes_v2/features/InterviewScene';
import { ResultsScene } from './scenes_v2/results/ResultsScene';
import { CtaScene } from './scenes_v2/cta/CtaScene';

const SCENES = {
  hook:        { from: 0,    duration: 180 },  // 6s
  problem:     { from: 180,  duration: 300 },  // 10s
  brand:       { from: 480,  duration: 210 },  // 7s
  cvBuilder:   { from: 690,  duration: 360 },  // 12s
  atsScore:    { from: 1050, duration: 330 },  // 11s
  interview:   { from: 1380, duration: 750 },  // 25s
  results:     { from: 2130, duration: 225 },  // 7.5s
  cta:         { from: 2355, duration: 285 },  // 9.5s
};

export const SeratyAdV2: React.FC = () => {
  return (
    <AbsoluteFill style={{ backgroundColor: '#09090b' }}>
      {/* === Visual sections === */}
      <Sequence from={SCENES.hook.from} durationInFrames={SCENES.hook.duration}>
        <HookScene />
      </Sequence>
      <Sequence from={SCENES.problem.from} durationInFrames={SCENES.problem.duration}>
        <AtsRejectionScene />
      </Sequence>
      <Sequence from={SCENES.brand.from} durationInFrames={SCENES.brand.duration}>
        <BrandIntroScene />
      </Sequence>
      <Sequence from={SCENES.cvBuilder.from} durationInFrames={SCENES.cvBuilder.duration}>
        <CvBuilderScene />
      </Sequence>
      <Sequence from={SCENES.atsScore.from} durationInFrames={SCENES.atsScore.duration}>
        <AtsScoreScene />
      </Sequence>
      <Sequence from={SCENES.interview.from} durationInFrames={SCENES.interview.duration}>
        <InterviewScene />
      </Sequence>
      <Sequence from={SCENES.results.from} durationInFrames={SCENES.results.duration}>
        <ResultsScene />
      </Sequence>
      <Sequence from={SCENES.cta.from} durationInFrames={SCENES.cta.duration}>
        <CtaScene />
      </Sequence>

      {/* === Per-scene narration == */}
      <Sequence from={SCENES.hook.from} durationInFrames={138}>
        <Audio src={staticFile('voice/v2-scene01.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.problem.from} durationInFrames={273}>
        <Audio src={staticFile('voice/v2-scene02.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.brand.from} durationInFrames={180}>
        <Audio src={staticFile('voice/v2-scene03.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.cvBuilder.from} durationInFrames={330}>
        <Audio src={staticFile('voice/v2-scene04.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.atsScore.from} durationInFrames={306}>
        <Audio src={staticFile('voice/v2-scene05.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.interview.from} durationInFrames={150}>
        <Audio src={staticFile('voice/v2-scene06.mp3')} volume={0.85} />
      </Sequence>

      {/* Interview conversation voices */}
      <Sequence from={SCENES.interview.from + 150} durationInFrames={189}>
        <Audio src={staticFile('voice/v2-recruiter.mp3')} volume={0.9} />
      </Sequence>
      <Sequence from={SCENES.interview.from + 300} durationInFrames={450}>
        <Audio src={staticFile('voice/v2-seeker.mp3')} volume={0.9} />
      </Sequence>

      <Sequence from={SCENES.results.from} durationInFrames={198}>
        <Audio src={staticFile('voice/v2-scene07.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.cta.from} durationInFrames={213}>
        <Audio src={staticFile('voice/v2-scene08.mp3')} volume={0.85} />
      </Sequence>

      {/* === SFX === */}
      {/* Scene 1: Hook - deflate when 0 appears */}
      <Sequence from={70} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/hook-deflate.wav')} volume={0.35} />
      </Sequence>

      {/* Scene 2: Problem - scan and reject */}
      <Sequence from={230} durationInFrames={35}>
        <Audio src={staticFile('sfx/v2/scan-digital.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={370} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/scan-reject.wav')} volume={0.35} />
      </Sequence>

      {/* Scene 3: Brand - chime */}
      <Sequence from={490} durationInFrames={30}>
        <Audio src={staticFile('sfx/v2/brand-chime.wav')} volume={0.35} />
      </Sequence>

      {/* Scene 4: CV Builder - typing */}
      <Sequence from={730} durationInFrames={40}>
        <Audio src={staticFile('sfx/v2/typing-subtle.wav')} volume={0.2} />
      </Sequence>

      {/* Scene 5: ATS Score - rising and complete */}
      <Sequence from={1090} durationInFrames={50}>
        <Audio src={staticFile('sfx/v2/score-rising.wav')} volume={0.25} />
      </Sequence>
      <Sequence from={1220} durationInFrames={35}>
        <Audio src={staticFile('sfx/v2/score-complete.wav')} volume={0.3} />
      </Sequence>

      {/* Scene 7: Results */}
      <Sequence from={2180} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/notification-pop.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={2230} durationInFrames={40}>
        <Audio src={staticFile('sfx/v2/success-chord.wav')} volume={0.3} />
      </Sequence>

      {/* Scene 8: CTA */}
      <Sequence from={2420} durationInFrames={20}>
        <Audio src={staticFile('sfx/v2/button-click.wav')} volume={0.3} />
      </Sequence>
    </AbsoluteFill>
  );
};
