import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const StrengthsCard: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const cardEntrance = spring({ frame, fps, config: { damping: 16, stiffness: 90 } });

  const strengths = [
    'Clear articulation of technical concepts',
    'Strong use of concrete examples',
    'Demonstrated leadership in past projects',
    'Good awareness of industry trends',
  ];

  const improvements = [
    'Could use more structured STAR responses',
    'Add more quantifiable achievements',
  ];

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', padding: 60 * scale }}>
      <div
        style={{
          opacity: cardEntrance,
          transform: `translateY(${interpolate(cardEntrance, [0, 1], [40, 0])}px)`,
          display: 'flex',
          flexDirection: 'column',
          gap: 24 * scale,
          width: LAYOUT.width - 100 * scale,
        }}
      >
        <div
          style={{
            background: COLORS.bgCard,
            backdropFilter: 'blur(24px)',
            borderRadius: 20 * scale,
            border: `1px solid ${COLORS.border5}`,
            borderLeft: `4px solid ${COLORS.emerald500}`,
            padding: `${28 * scale}px ${32 * scale}px`,
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 * scale, marginBottom: 20 * scale }}>
            <svg width={18 * scale} height={18 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={2}>
              <path d="M5 13l4 4L19 7" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
            <span style={{ color: COLORS.white, fontSize: 18 * scale, fontWeight: 700, fontFamily: FONT.primary }}>Top Strengths</span>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 * scale }}>
            {strengths.map((s, i) => (
              <div
                key={i}
                style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: 10 * scale,
                  opacity: interpolate(frame, [10 + i * 6, 18 + i * 6], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
                  transform: `translateX(${interpolate(frame, [10 + i * 6, 18 + i * 6], [20, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' })}px)`,
                }}
              >
                <span style={{ color: COLORS.emerald400, fontSize: 16 * scale, lineHeight: 1.4 }}>•</span>
                <span style={{ color: COLORS.zinc300, fontSize: 14 * scale, fontFamily: FONT.primary, lineHeight: 1.5 }}>{s}</span>
              </div>
            ))}
          </div>
        </div>

        <div
          style={{
            background: COLORS.bgCard,
            backdropFilter: 'blur(24px)',
            borderRadius: 20 * scale,
            border: `1px solid ${COLORS.border5}`,
            borderLeft: `4px solid ${COLORS.amber500}`,
            padding: `${28 * scale}px ${32 * scale}px`,
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 * scale, marginBottom: 20 * scale }}>
            <svg width={18 * scale} height={18 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.amber400} strokeWidth={2}>
              <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
            <span style={{ color: COLORS.white, fontSize: 18 * scale, fontWeight: 700, fontFamily: FONT.primary }}>Areas to Improve</span>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 * scale }}>
            {improvements.map((s, i) => (
              <div
                key={i}
                style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: 10 * scale,
                  opacity: interpolate(frame, [30 + i * 6, 38 + i * 6], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
                  transform: `translateX(${interpolate(frame, [30 + i * 6, 38 + i * 6], [20, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' })}px)`,
                }}
              >
                <span style={{ color: COLORS.amber400, fontSize: 16 * scale, lineHeight: 1.4 }}>•</span>
                <span style={{ color: COLORS.zinc300, fontSize: 14 * scale, fontFamily: FONT.primary, lineHeight: 1.5 }}>{s}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
