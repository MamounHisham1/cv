import React from 'react';
import { AbsoluteFill, Sequence, Audio, staticFile } from 'remotion';
import { ColdOpen } from './scenes/interviewer/ColdOpen';
import { HookReveal } from './scenes/interviewer/HookReveal';
import { TitleCard } from './scenes/interviewer/TitleCard';
import { SetupShowcase } from './scenes/interviewer/SetupShowcase';
import { SetupCVSelect } from './scenes/interviewer/SetupCVSelect';
import { SetupTypes } from './scenes/interviewer/SetupTypes';
import { StartButton } from './scenes/interviewer/StartButton';
import { Connecting } from './scenes/interviewer/Connecting';
import { InterviewActive } from './scenes/interviewer/InterviewActive';
import { WaveformCloseup } from './scenes/interviewer/WaveformCloseup';
import { InterviewListening } from './scenes/interviewer/InterviewListening';
import { InterviewExchange } from './scenes/interviewer/InterviewExchange';
import { InterviewEnd } from './scenes/interviewer/InterviewEnd';
import { Evaluating } from './scenes/interviewer/Evaluating';
import { ScoreReveal } from './scenes/interviewer/ScoreReveal';
import { CriteriaBars } from './scenes/interviewer/CriteriaBars';
import { StrengthsCard } from './scenes/interviewer/StrengthsCard';
import { FreeFirstTime } from './scenes/interviewer/FreeFirstTime';
import { SeratyTransition } from './scenes/recap/SeratyTransition';
import { FeatureBuilder } from './scenes/recap/FeatureBuilder';
import { FeatureTemplates } from './scenes/recap/FeatureTemplates';
import { FeatureATS } from './scenes/recap/FeatureATS';
import { FeatureEvaluator } from './scenes/recap/FeatureEvaluator';
import { FeatureInterviewerRecap } from './scenes/recap/FeatureInterviewerRecap';
import { FeatureMontage } from './scenes/recap/FeatureMontage';
import { CreditsCard } from './scenes/recap/CreditsCard';
import { FinalCTA } from './scenes/cta/FinalCTA';
import { EndCard } from './scenes/cta/EndCard';

const SCENES = {
  coldOpen:        { from: 0,    duration: 75 },
  hookReveal:      { from: 75,   duration: 60 },
  titleCard:       { from: 135,  duration: 60 },
  setupShowcase:   { from: 195,  duration: 75 },
  setupCV:         { from: 270,  duration: 45 },
  setupTypes:      { from: 315,  duration: 60 },
  startButton:     { from: 375,  duration: 45 },
  connecting:      { from: 420,  duration: 30 },
  interviewActive: { from: 450,  duration: 90 },
  waveformCloseup: { from: 540,  duration: 60 },
  interviewListen: { from: 600,  duration: 45 },
  interviewExch:   { from: 645,  duration: 75 },
  interviewEnd:    { from: 720,  duration: 45 },
  evaluating:      { from: 765,  duration: 45 },
  scoreReveal:     { from: 810,  duration: 75 },
  criteriaBars:    { from: 885,  duration: 60 },
  strengthsCard:   { from: 945,  duration: 45 },
  freeFirstTime:   { from: 990,  duration: 75 },
  seratyTransition:{ from: 1065, duration: 45 },
  featureBuilder:  { from: 1110, duration: 60 },
  featureTemplates:{ from: 1170, duration: 60 },
  featureATS:      { from: 1230, duration: 60 },
  featureEvaluator:{ from: 1290, duration: 60 },
  featureInterview:{ from: 1350, duration: 60 },
  featureMontage:  { from: 1410, duration: 60 },
  creditsCard:     { from: 1470, duration: 60 },
  finalCTA:        { from: 1530, duration: 180 },
  endCard:         { from: 1710, duration: 90 },
};

export const SeratyAd: React.FC = () => {
  return (
    <AbsoluteFill style={{ backgroundColor: '#09090b' }}>
      <Sequence from={SCENES.coldOpen.from} durationInFrames={SCENES.coldOpen.duration}>
        <ColdOpen />
      </Sequence>

      <Sequence from={SCENES.hookReveal.from} durationInFrames={SCENES.hookReveal.duration}>
        <HookReveal />
      </Sequence>

      <Sequence from={SCENES.titleCard.from} durationInFrames={SCENES.titleCard.duration}>
        <TitleCard />
      </Sequence>

      <Sequence from={SCENES.setupShowcase.from} durationInFrames={SCENES.setupShowcase.duration}>
        <SetupShowcase />
      </Sequence>

      <Sequence from={SCENES.setupCV.from} durationInFrames={SCENES.setupCV.duration}>
        <SetupCVSelect />
      </Sequence>

      <Sequence from={SCENES.setupTypes.from} durationInFrames={SCENES.setupTypes.duration}>
        <SetupTypes />
      </Sequence>

      <Sequence from={SCENES.startButton.from} durationInFrames={SCENES.startButton.duration}>
        <StartButton />
      </Sequence>

      <Sequence from={SCENES.connecting.from} durationInFrames={SCENES.connecting.duration}>
        <Connecting />
      </Sequence>

      <Sequence from={SCENES.interviewActive.from} durationInFrames={SCENES.interviewActive.duration}>
        <InterviewActive />
      </Sequence>

      <Sequence from={SCENES.waveformCloseup.from} durationInFrames={SCENES.waveformCloseup.duration}>
        <WaveformCloseup />
      </Sequence>

      <Sequence from={SCENES.interviewListen.from} durationInFrames={SCENES.interviewListen.duration}>
        <InterviewListening />
      </Sequence>

      <Sequence from={SCENES.interviewExch.from} durationInFrames={SCENES.interviewExch.duration}>
        <InterviewExchange />
      </Sequence>

      <Sequence from={SCENES.interviewEnd.from} durationInFrames={SCENES.interviewEnd.duration}>
        <InterviewEnd />
      </Sequence>

      <Sequence from={SCENES.evaluating.from} durationInFrames={SCENES.evaluating.duration}>
        <Evaluating />
      </Sequence>

      <Sequence from={SCENES.scoreReveal.from} durationInFrames={SCENES.scoreReveal.duration}>
        <ScoreReveal />
      </Sequence>

      <Sequence from={SCENES.criteriaBars.from} durationInFrames={SCENES.criteriaBars.duration}>
        <CriteriaBars />
      </Sequence>

      <Sequence from={SCENES.strengthsCard.from} durationInFrames={SCENES.strengthsCard.duration}>
        <StrengthsCard />
      </Sequence>

      <Sequence from={SCENES.freeFirstTime.from} durationInFrames={SCENES.freeFirstTime.duration}>
        <FreeFirstTime />
      </Sequence>

      <Sequence from={SCENES.seratyTransition.from} durationInFrames={SCENES.seratyTransition.duration}>
        <SeratyTransition />
      </Sequence>

      <Sequence from={SCENES.featureBuilder.from} durationInFrames={SCENES.featureBuilder.duration}>
        <FeatureBuilder />
      </Sequence>

      <Sequence from={SCENES.featureTemplates.from} durationInFrames={SCENES.featureTemplates.duration}>
        <FeatureTemplates />
      </Sequence>

      <Sequence from={SCENES.featureATS.from} durationInFrames={SCENES.featureATS.duration}>
        <FeatureATS />
      </Sequence>

      <Sequence from={SCENES.featureEvaluator.from} durationInFrames={SCENES.featureEvaluator.duration}>
        <FeatureEvaluator />
      </Sequence>

      <Sequence from={SCENES.featureInterview.from} durationInFrames={SCENES.featureInterview.duration}>
        <FeatureInterviewerRecap />
      </Sequence>

      <Sequence from={SCENES.featureMontage.from} durationInFrames={SCENES.featureMontage.duration}>
        <FeatureMontage />
      </Sequence>

      <Sequence from={SCENES.creditsCard.from} durationInFrames={SCENES.creditsCard.duration}>
        <CreditsCard />
      </Sequence>

      <Sequence from={SCENES.finalCTA.from} durationInFrames={SCENES.finalCTA.duration}>
        <FinalCTA />
      </Sequence>

       <Sequence from={SCENES.endCard.from} durationInFrames={SCENES.endCard.duration}>
        <EndCard />
      </Sequence>

      {/* Sound Effects */}
      <Sequence from={70} durationInFrames={30}>
        <Audio src={staticFile('sfx/glitch.wav')} volume={0.6} />
      </Sequence>
      <Sequence from={130} durationInFrames={30}>
        <Audio src={staticFile('sfx/reveal.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={193} durationInFrames={20}>
        <Audio src={staticFile('sfx/swoosh.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={268} durationInFrames={15}>
        <Audio src={staticFile('sfx/click.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={313} durationInFrames={20}>
        <Audio src={staticFile('sfx/digital-press.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={373} durationInFrames={20}>
        <Audio src={staticFile('sfx/impact.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={418} durationInFrames={15}>
        <Audio src={staticFile('sfx/pulse.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={448} durationInFrames={20}>
        <Audio src={staticFile('sfx/reveal.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={538} durationInFrames={15}>
        <Audio src={staticFile('sfx/swoosh.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={643} durationInFrames={20}>
        <Audio src={staticFile('sfx/tick.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={718} durationInFrames={20}>
        <Audio src={staticFile('sfx/success.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={763} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={808} durationInFrames={40}>
        <Audio src={staticFile('sfx/score-reveal.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={943} durationInFrames={15}>
        <Audio src={staticFile('sfx/pop.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={988} durationInFrames={30}>
        <Audio src={staticFile('sfx/free-badge.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={1063} durationInFrames={20}>
        <Audio src={staticFile('sfx/rising-whoosh.wav')} volume={0.5} />
      </Sequence>
      <Sequence from={1108} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={1168} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={1228} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={1288} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={1348} durationInFrames={15}>
        <Audio src={staticFile('sfx/slide-in.wav')} volume={0.35} />
      </Sequence>
      <Sequence from={1408} durationInFrames={15}>
        <Audio src={staticFile('sfx/glitch.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={1468} durationInFrames={20}>
        <Audio src={staticFile('sfx/chime.wav')} volume={0.3} />
      </Sequence>
      <Sequence from={1528} durationInFrames={30}>
        <Audio src={staticFile('sfx/whoosh.wav')} volume={0.4} />
      </Sequence>
      <Sequence from={1700} durationInFrames={30}>
        <Audio src={staticFile('sfx/soft-pad.wav')} volume={0.25} />
      </Sequence>
    </AbsoluteFill>
  );
};
