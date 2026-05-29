import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { InterviewSetupCard } from '../../components/InterviewUI';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

export const StartButton: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  // Click timing: trigger click press and visual ripple starting from frame 25
  const triggerClickRipple = frame >= 25;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', paddingTop: 120 * scale, overflow: 'hidden' }}>
      <OrbSet visible={true} />
      <div style={{ zIndex: 1 }}>
        <InterviewSetupCard
          frame={frame}
          selectedType="mixed"
          highlightSection="all"
          focusSection="button"
          dropdownOpen={false}
          dropdownSelectedIndex={1} // Select CV is Full Stack Developer
          triggerClickRipple={triggerClickRipple}
        />
      </div>
    </AbsoluteFill>
  );
};
