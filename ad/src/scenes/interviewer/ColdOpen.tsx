import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const ColdOpen: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  const line1Opacity = interpolate(frame, [5, 20], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const line1Y = interpolate(frame, [5, 20], [30, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const line2Opacity = interpolate(frame, [30, 50], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const line2Y = interpolate(frame, [30, 50], [30, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const line3Opacity = interpolate(frame, [55, 70], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const glitchOffset = frame < 5 ? Math.random() * 6 - 3 : 0;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', padding: 80 * scale }}>
      <div style={{ transform: `translateX(${glitchOffset}px)`, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 20 * scale }}>
        <div
          style={{
            opacity: line1Opacity,
            transform: `translateY(${line1Y}px)`,
            color: COLORS.zinc400,
            fontSize: 28 * scale,
            fontWeight: 500,
            fontFamily: FONT.primary,
            letterSpacing: '0.05em',
          }}
        >
          What if...
        </div>
        <div
          style={{
            opacity: line2Opacity,
            transform: `translateY(${line2Y}px)`,
            color: COLORS.white,
            fontSize: 52 * scale,
            fontWeight: 800,
            fontFamily: FONT.primary,
            letterSpacing: '-1.5px',
            textAlign: 'center',
            lineHeight: 1.2,
          }}
        >
          AI could be
          <br />
          your <span style={{ color: COLORS.emerald400 }}>interviewer</span>?
        </div>
        <div
          style={{
            opacity: line3Opacity,
            height: 3 * scale,
            width: 120 * scale,
            background: `linear-gradient(90deg, transparent, ${COLORS.emerald500}, transparent)`,
            borderRadius: 2,
            marginTop: 16 * scale,
          }}
        />
      </div>
    </AbsoluteFill>
  );
};
