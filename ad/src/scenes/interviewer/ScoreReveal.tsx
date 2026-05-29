import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { ScoreRing } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const ScoreReveal: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const score = 87;
  const grade = 'A-';

  const cardEntrance = spring({ frame, fps, config: { damping: 16, stiffness: 80 } });
  const cardY = interpolate(cardEntrance, [0, 1], [60, 0]);

  const gradeOpacity = interpolate(frame, [30, 45], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const summaryOpacity = interpolate(frame, [40, 55], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', padding: 60 * scale }}>
      <div
        style={{
          transform: `translateY(${cardY}px)`,
          opacity: cardEntrance,
          background: COLORS.bgCard,
          backdropFilter: 'blur(24px)',
          borderRadius: 28 * scale,
          border: `1px solid ${COLORS.border5}`,
          padding: `${48 * scale}px`,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: 32 * scale,
          width: LAYOUT.width - 100 * scale,
          boxShadow: `0 0 60px ${COLORS.emerald500}08`,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 48 * scale }}>
          <ScoreRing frame={frame} targetScore={score} size={200} delay={5} />
          <div>
            <div
              style={{
                opacity: gradeOpacity,
                display: 'inline-flex',
                alignItems: 'center',
                padding: `${6 * scale}px ${16 * scale}px`,
                borderRadius: 10 * scale,
                background: COLORS.surface10,
                color: COLORS.white,
                fontSize: 14 * scale,
                fontWeight: 500,
                fontFamily: FONT.primary,
                marginBottom: 16 * scale,
              }}
            >
              Grade: {grade}
            </div>
            <div style={{ opacity: summaryOpacity }}>
              <div style={{ color: COLORS.white, fontSize: 22 * scale, fontWeight: 700, fontFamily: FONT.primary, marginBottom: 10 * scale }}>
                Executive Summary
              </div>
              <div style={{ color: COLORS.zinc300, fontSize: 15 * scale, fontFamily: FONT.primary, lineHeight: 1.6, maxWidth: 450 * scale }}>
                Strong interview performance with clear communication and solid technical grounding.
              </div>
            </div>
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
