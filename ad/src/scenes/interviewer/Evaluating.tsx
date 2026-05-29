import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const Evaluating: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;
  const rotation = frame * 12;

  const fadeIn = interpolate(frame, [0, 10], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div style={{ opacity: fadeIn, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48 * scale }}>
        <div style={{ position: 'relative', width: 100 * scale, height: 100 * scale }}>
          <svg
            style={{ transform: `rotate(${rotation}deg)` }}
            width={100 * scale}
            height={100 * scale}
            viewBox="0 0 24 24"
            fill="none"
          >
            <circle cx="12" cy="12" r="10" stroke={COLORS.zinc800} strokeWidth={3} />
            <path fill={COLORS.emerald400} d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" opacity={0.8} />
          </svg>
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 12 * scale }}>
          <div style={{ color: COLORS.white, fontSize: 26 * scale, fontWeight: 700, fontFamily: FONT.primary }}>
            Analyzing Your Performance
          </div>
          <div style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, textAlign: 'center', maxWidth: 600 * scale, lineHeight: 1.6 }}>
            The AI is reviewing the transcript to provide structured feedback and a final grade.
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
