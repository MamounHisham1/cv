import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { InterviewSetupCard } from '../../components/InterviewUI';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

export const SetupTypes: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  // Toggle active interview focus card based on frame:
  // - Frame < 15: Behavioral
  // - Frame 15 to 30: Technical
  // - Frame 30+: Mixed
  const selectedType = frame < 15 ? 'behavioral' : frame < 30 ? 'technical' : 'mixed';

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', paddingTop: 120 * scale, overflow: 'hidden' }}>
      <OrbSet visible={true} />
      <div style={{ zIndex: 1 }}>
        <InterviewSetupCard
          frame={frame}
          selectedType={selectedType}
          highlightSection="all"
          focusSection="type"
          dropdownOpen={false}
          dropdownSelectedIndex={1} // Keep the CV as Full Stack Developer
        />
      </div>
    </AbsoluteFill>
  );
};
