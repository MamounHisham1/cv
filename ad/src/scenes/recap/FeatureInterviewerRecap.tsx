import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureInterviewerRecap: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <FeatureRecapCard
        frame={frame}
        icon={
          <svg width={28 * scale} height={28 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M19 10v1a7 7 0 01-14 0v-1M12 18v4M8 22h8" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        }
        title="AI Interviewer"
        description="Practice with voice-based mock interviews. Get scored on 6 criteria with detailed feedback."
        accentColor={COLORS.emerald400}
      />
    </AbsoluteFill>
  );
};
