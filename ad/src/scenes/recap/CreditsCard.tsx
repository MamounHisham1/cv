import React from 'react';
import { AbsoluteFill, useCurrentFrame } from 'remotion';
import { CreditCard } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const CreditsCard: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <CreditCard frame={frame} />
    </AbsoluteFill>
  );
};
