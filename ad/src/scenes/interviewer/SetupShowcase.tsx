import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { InterviewSetupCard } from '../../components/InterviewUI';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const SetupShowcase: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const cardEntrance = spring({ frame, fps, config: { damping: 18, stiffness: 90 } });
  const cardY = interpolate(cardEntrance, [0, 1], [80, 0]);

  const topLabel = interpolate(frame, [0, 15], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', paddingTop: 120 * scale, overflow: 'hidden' }}>
      <OrbSet visible={true} />
      <div style={{ opacity: topLabel, marginBottom: 40 * scale, zIndex: 1 }}>
        <span style={{ color: COLORS.emerald400, fontSize: 14 * scale, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.15em', textTransform: 'uppercase' }}>
          Step 1
        </span>
      </div>
      <div style={{ transform: `translateY(${cardY}px)`, opacity: cardEntrance, zIndex: 1 }}>
        <InterviewSetupCard frame={frame} selectedType="mixed" highlightSection="all" focusSection="none" />
      </div>
    </AbsoluteFill>
  );
};
