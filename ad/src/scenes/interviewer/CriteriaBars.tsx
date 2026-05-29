import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { CriteriaBar } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const CriteriaBars: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const criteria = [
    { label: 'Communication Clarity', score: 9 },
    { label: 'Technical Depth', score: 7 },
    { label: 'Confidence & Composure', score: 8 },
    { label: 'STAR Method Usage', score: 6 },
    { label: 'Relevance to Role', score: 9 },
    { label: 'Specificity & Examples', score: 7 },
  ];

  const headerEntrance = spring({ frame, fps, config: { damping: 16, stiffness: 90 } });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', padding: 60 * scale }}>
      <div
        style={{
          opacity: headerEntrance,
          background: COLORS.bgCard,
          backdropFilter: 'blur(24px)',
          borderRadius: 24 * scale,
          border: `1px solid ${COLORS.border5}`,
          padding: `${36 * scale}px ${40 * scale}px`,
          width: LAYOUT.width - 100 * scale,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 * scale, marginBottom: 32 * scale }}>
          <svg width={20 * scale} height={20 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={2}>
            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
          <span style={{ color: COLORS.white, fontSize: 20 * scale, fontWeight: 700, fontFamily: FONT.primary }}>Score Breakdown</span>
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: 20 * scale }}>
          {criteria.map((c, i) => (
            <CriteriaBar key={c.label} frame={frame} label={c.label} score={c.score} delay={i * 6} />
          ))}
        </div>
      </div>
    </AbsoluteFill>
  );
};
