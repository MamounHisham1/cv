import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureATS: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <FeatureRecapCard
        frame={frame}
        icon={
          <svg width={28 * scale} height={28 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
            <rect x="4" y="4" width="16" height="16" rx="2" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M9 9H15V15H9V9Z" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M9 1V4M15 1V4M9 20V23M15 20V23M20 9H23M20 15H23M1 9H4M1 15H4" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        }
        title="ATS Optimization"
        description="AI evaluates your CV against 17K+ resume benchmarks. Score high, get noticed."
        accentColor={COLORS.purple400}
      />
    </AbsoluteFill>
  );
};
