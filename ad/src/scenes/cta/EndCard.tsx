import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const EndCard: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const fadeOut = interpolate(frame, [100, 120], [1, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill
      style={{
        backgroundColor: COLORS.bg,
        justifyContent: 'center',
        alignItems: 'center',
        opacity: fadeOut,
      }}
    >
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48 * scale }}>
        <SeratyLogo size="medium" />
        <div
          style={{
            color: COLORS.zinc500,
            fontSize: 16 * scale,
            fontFamily: FONT.primary,
            textAlign: 'center',
            lineHeight: 1.8,
          }}
        >
          Build, Enhance & Evaluate Your CV
          <br />
          <span style={{ color: COLORS.emerald400 }}>All with AI</span>
        </div>
        <div
          style={{
            color: COLORS.zinc600,
            fontSize: 14 * scale,
            fontFamily: FONT.primary,
            letterSpacing: '0.1em',
            textTransform: 'uppercase',
          }}
        >
          seraty.softland.tech
        </div>
      </div>
    </AbsoluteFill>
  );
};
