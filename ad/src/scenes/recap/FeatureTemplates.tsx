import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureTemplates: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <FeatureRecapCard
        frame={frame}
        icon={
          <svg width={28 * scale} height={28 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        }
        title="10 Professional Templates"
        description="From classic to creative, find the perfect template. ATS-optimized and recruiter-approved."
        accentColor={COLORS.blue400}
      />
    </AbsoluteFill>
  );
};
