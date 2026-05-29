import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureEvaluator: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <FeatureRecapCard
        frame={frame}
        icon={
          <svg width={28 * scale} height={28 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
            <path d="M18 20V10M12 20V4M6 20v-6" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        }
        title="AI Evaluator"
        description="Get detailed feedback on your CV. 10 evaluation criteria with actionable insights."
        accentColor={COLORS.amber400}
      />
    </AbsoluteFill>
  );
};
