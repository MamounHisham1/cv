import React from 'react';
import { AbsoluteFill, Sequence, Audio, staticFile } from 'remotion';
import { HookSceneArabic } from './scenes_v2_arabic/hook/HookSceneArabic';
import { AtsRejectionSceneArabic } from './scenes_v2_arabic/problem/AtsRejectionSceneArabic';
import { BrandIntroSceneArabic } from './scenes_v2_arabic/solution/BrandIntroSceneArabic';
import { CvBuilderSceneArabic } from './scenes_v2_arabic/features/CvBuilderSceneArabic';
import { AtsScoreSceneArabic } from './scenes_v2_arabic/features/AtsScoreSceneArabic';
import { InterviewSceneArabic } from './scenes_v2_arabic/features/InterviewSceneArabic';
import { ResultsSceneArabic } from './scenes_v2_arabic/results/ResultsSceneArabic';
import { CtaSceneArabic } from './scenes_v2_arabic/cta/CtaSceneArabic';

const SCENES = {
  hook:      { from: 0,    duration: 250 },  // 8.3s
  problem:   { from: 250,  duration: 420 },  // 14s
  brand:     { from: 670,  duration: 260 },  // 8.7s
  cvBuilder: { from: 930,  duration: 350 },  // 11.7s
  atsScore:  { from: 1280, duration: 350 },  // 11.7s
  interview: { from: 1630, duration: 660 },  // 22s
  results:   { from: 2290, duration: 310 },  // 10.3s
  cta:       { from: 2600, duration: 310 },  // 10.3s
};

export const SeratyAdArabicV2: React.FC = () => {
  return (
    <AbsoluteFill style={{ backgroundColor: '#09090b' }}>
      {/* === Visual sections === */}
      <Sequence from={SCENES.hook.from} durationInFrames={SCENES.hook.duration}>
        <HookSceneArabic />
      </Sequence>
      <Sequence from={SCENES.problem.from} durationInFrames={SCENES.problem.duration}>
        <AtsRejectionSceneArabic />
      </Sequence>
      <Sequence from={SCENES.brand.from} durationInFrames={SCENES.brand.duration}>
        <BrandIntroSceneArabic />
      </Sequence>
      <Sequence from={SCENES.cvBuilder.from} durationInFrames={SCENES.cvBuilder.duration}>
        <CvBuilderSceneArabic />
      </Sequence>
      <Sequence from={SCENES.atsScore.from} durationInFrames={SCENES.atsScore.duration}>
        <AtsScoreSceneArabic />
      </Sequence>
      <Sequence from={SCENES.interview.from} durationInFrames={SCENES.interview.duration}>
        <InterviewSceneArabic />
      </Sequence>
      <Sequence from={SCENES.results.from} durationInFrames={SCENES.results.duration}>
        <ResultsSceneArabic />
      </Sequence>
      <Sequence from={SCENES.cta.from} durationInFrames={SCENES.cta.duration}>
        <CtaSceneArabic />
      </Sequence>

      {/* === Per-scene narration (Egyptian Arabic) === */}
      <Sequence from={SCENES.hook.from} durationInFrames={227}>
        <Audio src={staticFile('voice/v2ar-scene01.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.problem.from} durationInFrames={404}>
        <Audio src={staticFile('voice/v2ar-scene02.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.brand.from} durationInFrames={241}>
        <Audio src={staticFile('voice/v2ar-scene03.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.cvBuilder.from} durationInFrames={332}>
        <Audio src={staticFile('voice/v2ar-scene04.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.atsScore.from} durationInFrames={329}>
        <Audio src={staticFile('voice/v2ar-scene05.mp3')} volume={0.85} />
      </Sequence>

      {/* Interview: narration ends before recruiter voice starts */}
      <Sequence from={SCENES.interview.from} durationInFrames={120}>
        <Audio src={staticFile('voice/v2ar-scene06.mp3')} volume={0.85} />
      </Sequence>

      {/* Interview conversation voices (in English) */}
      <Sequence from={SCENES.interview.from + 150} durationInFrames={101}>
        <Audio src={staticFile('voice/v2ar-recruiter.mp3')} volume={0.9} />
      </Sequence>
      <Sequence from={SCENES.interview.from + 300} durationInFrames={342}>
        <Audio src={staticFile('voice/v2ar-seeker.mp3')} volume={0.9} />
      </Sequence>

      <Sequence from={SCENES.results.from} durationInFrames={294}>
        <Audio src={staticFile('voice/v2ar-scene07.mp3')} volume={0.85} />
      </Sequence>
      <Sequence from={SCENES.cta.from} durationInFrames={291}>
        <Audio src={staticFile('voice/v2ar-scene08.mp3')} volume={0.85} />
      </Sequence>

      {/* === SFX (same as English version, shifted) === */}
      <Sequence from={70} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/hook-deflate.wav')} volume={0.35} />
      </Sequence>

      <Sequence from={300} durationInFrames={35}>
        <Audio src={staticFile('sfx/v2/scan-digital.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={440} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/scan-reject.wav')} volume={0.35} />
      </Sequence>

      <Sequence from={680} durationInFrames={30}>
        <Audio src={staticFile('sfx/v2/brand-chime.wav')} volume={0.35} />
      </Sequence>

      <Sequence from={970} durationInFrames={40}>
        <Audio src={staticFile('sfx/v2/typing-subtle.wav')} volume={0.2} />
      </Sequence>

      <Sequence from={1320} durationInFrames={50}>
        <Audio src={staticFile('sfx/v2/score-rising.wav')} volume={0.25} />
      </Sequence>
      <Sequence from={1450} durationInFrames={35}>
        <Audio src={staticFile('sfx/v2/score-complete.wav')} volume={0.3} />
      </Sequence>

      <Sequence from={2340} durationInFrames={25}>
        <Audio src={staticFile('sfx/v2/notification-pop.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={2390} durationInFrames={40}>
        <Audio src={staticFile('sfx/v2/success-chord.wav')} volume={0.3} />
      </Sequence>

      <Sequence from={2665} durationInFrames={20}>
        <Audio src={staticFile('sfx/v2/button-click.wav')} volume={0.3} />
      </Sequence>
    </AbsoluteFill>
  );
};
