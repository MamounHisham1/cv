import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const InterviewEnd: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const titleScale = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 14, stiffness: 80 } });
  const checkScale = spring({ frame: Math.max(0, frame - 15), fps, config: { damping: 12, stiffness: 70 } });

  const statsOpacity = interpolate(frame, [20, 35], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 40 * scale }}>
        <div
          style={{
            width: 100 * scale,
            height: 100 * scale,
            borderRadius: '50%',
            background: `${COLORS.emerald500}15`,
            border: `2px solid ${COLORS.emerald500}30`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            transform: `scale(${checkScale})`,
            boxShadow: `0 0 40px ${COLORS.emerald500}15`,
          }}
        >
          <svg width={48 * scale} height={48 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={2}>
            <path d="M5 13l4 4L19 7" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>

        <div style={{ transform: `scale(${titleScale})`, textAlign: 'center' }}>
          <div style={{ color: COLORS.white, fontSize: 36 * scale, fontWeight: 700, fontFamily: FONT.primary, letterSpacing: '-1px' }}>
            Interview Complete
          </div>
        </div>

        <div style={{ opacity: statsOpacity, display: 'flex', gap: 48 * scale }}>
          {[
            { label: 'Duration', value: '12:34' },
            { label: 'Questions', value: '8' },
            { label: 'Turns', value: '16' },
          ].map((stat) => (
            <div key={stat.label} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6 * scale }}>
              <div style={{ color: COLORS.white, fontSize: 24 * scale, fontWeight: 700, fontFamily: FONT.primary }}>{stat.value}</div>
              <div style={{ color: COLORS.zinc500, fontSize: 12 * scale, fontFamily: FONT.primary, textTransform: 'uppercase', letterSpacing: '0.1em' }}>{stat.label}</div>
            </div>
          ))}
        </div>
      </div>
    </AbsoluteFill>
  );
};
