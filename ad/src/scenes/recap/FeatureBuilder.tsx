import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureBuilder: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <FeatureRecapCard
        frame={frame}
        icon={
          <svg width={28 * scale} height={28 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        }
        title="AI-Powered Builder"
        description="Build professional CVs with AI assistance. Smart suggestions, real-time preview, and instant formatting."
        accentColor={COLORS.emerald400}
      />
    </AbsoluteFill>
  );
};
