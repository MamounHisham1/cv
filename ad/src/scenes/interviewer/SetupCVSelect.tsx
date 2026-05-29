import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate } from 'remotion';
import { InterviewSetupCard } from '../../components/InterviewUI';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

export const SetupCVSelect: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  // Timeline for dropdown selection:
  // - Frame 0-8: Card zooms into CV selector.
  // - Frame 8-25: Dropdown opens, showing list. Hover/scroll moves down.
  // - Frame 25: User selects "Full Stack Developer" (index 1).
  // - Frame 30+: Dropdown closes, showing new selection.
  const dropdownOpen = frame >= 8 && frame < 30;
  const dropdownSelectedIndex = frame < 25 ? 0 : 1;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', paddingTop: 120 * scale, overflow: 'hidden' }}>
      <OrbSet visible={true} />
      <div style={{ zIndex: 1 }}>
        <InterviewSetupCard
          frame={frame}
          selectedType="mixed"
          highlightSection="all"
          focusSection="cv"
          dropdownOpen={dropdownOpen}
          dropdownSelectedIndex={dropdownSelectedIndex}
        />
      </div>
    </AbsoluteFill>
  );
};
