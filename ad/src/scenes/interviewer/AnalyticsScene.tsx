import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { ScoreRing } from '../../components/InterviewUI';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const AnalyticsScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Title entrance (frame 5 to 25)
  const titleEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 90 } });
  const titleOpacity = titleEntrance;
  const titleY = interpolate(titleEntrance, [0, 1], [-20 * scale, 0]);

  // Dashboard card entrance (frame 20 to 45)
  const dbEntrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 16, stiffness: 80 } });
  const dbScale = interpolate(dbEntrance, [0, 1], [0.85, 1]);
  const dbOpacity = dbEntrance;
  const dbY = interpolate(dbEntrance, [0, 1], [50 * scale, 0]);

  // Custom mini vertical progress bars for metrics
  const metrics = [
    { label: 'Communication', score: 85, delay: 35, color: COLORS.emerald400 },
    { label: 'Technical Depth', score: 92, delay: 50, color: COLORS.blue400 },
    { label: 'Confidence', score: 80, delay: 65, color: COLORS.purple400 },
  ];

  // Feedback tags entrance (frame 70 to 95)
  const tag1Entrance = spring({ frame: Math.max(0, frame - 70), fps, config: { damping: 15, stiffness: 85 } });
  const tag2Entrance = spring({ frame: Math.max(0, frame - 80), fps, config: { damping: 15, stiffness: 85 } });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', overflow: 'hidden', paddingTop: 130 * scale, paddingLeft: 80 * scale, paddingRight: 80 * scale }}>
      <OrbSet visible={true} />

      {/* --- TOP: Title --- */}
      <div style={{ opacity: titleOpacity, transform: `translateY(${titleY}px)`, textAlign: 'center', marginBottom: 40 * scale, zIndex: 10 }}>
        <span style={{ color: COLORS.emerald400, fontSize: 13 * scale, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.15em', textTransform: 'uppercase', display: 'block', marginBottom: 8 * scale }}>
          Instant Review
        </span>
        <h2 style={{ color: COLORS.white, fontSize: 36 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-1.5px', lineHeight: 1.2 }}>
          Real-Time Analytics
        </h2>
      </div>

      {/* --- MAIN GLASS DASHBOARD CARD --- */}
      <div
        style={{
          width: LAYOUT.width - 160 * scale,
          background: COLORS.bgCard,
          backdropFilter: 'blur(24px)',
          border: `1.5px solid ${COLORS.border}`,
          borderRadius: 24 * scale,
          padding: `${36 * scale}px ${32 * scale}px`,
          boxShadow: '0 25px 60px rgba(0,0,0,0.7)',
          transform: `scale(${dbScale}) translateY(${dbY}px)`,
          opacity: dbOpacity,
          zIndex: 1,
          display: 'flex',
          flexDirection: 'column',
          gap: 36 * scale,
        }}
      >
        {/* Metric Rings Row */}
        <div style={{ display: 'flex', justifyContent: 'space-between', gap: 10 * scale }}>
          {metrics.map((m) => (
            <div key={m.label} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16 * scale }}>
              <ScoreRing frame={frame} targetScore={m.score} delay={m.delay} size={130 * scale} />
              <span style={{ color: COLORS.zinc300, fontSize: 13 * scale, fontWeight: 600, fontFamily: FONT.primary, textAlign: 'center' }}>
                {m.label}
              </span>
            </div>
          ))}
        </div>

        <hr style={{ border: 'none', borderTop: `1px solid ${COLORS.border5}`, margin: 0 }} />

        {/* Structured Strengths / Areas of Improvement */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 16 * scale }}>
          <label style={{ color: COLORS.zinc500, fontSize: 11 * scale, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            Recruiter Insights
          </label>
          
          <div style={{ display: 'flex', flexDirection: 'column', gap: 12 * scale }}>
            {/* Strength Card */}
            <div
              style={{
                opacity: tag1Entrance,
                transform: `translateX(${interpolate(tag1Entrance, [0, 1], [-20 * scale, 0])}px)`,
                background: `${COLORS.emerald500}08`,
                border: `1.2px solid ${COLORS.emerald500}20`,
                borderRadius: 14 * scale,
                padding: `${12 * scale}px ${16 * scale}px`,
                display: 'flex',
                alignItems: 'center',
                gap: 12 * scale,
              }}
            >
              <div style={{ color: COLORS.emerald400, fontWeight: 800, fontSize: 18 * scale }}>✓</div>
              <span style={{ color: COLORS.white, fontSize: 14 * scale, fontWeight: 500, fontFamily: FONT.primary }}>
                Strong execution of STAR method structures.
              </span>
            </div>

            {/* Improvement Card */}
            <div
              style={{
                opacity: tag2Entrance,
                transform: `translateX(${interpolate(tag2Entrance, [0, 1], [20 * scale, 0])}px)`,
                background: `${COLORS.red500}08`,
                border: `1.2px solid ${COLORS.red500}20`,
                borderRadius: 14 * scale,
                padding: `${12 * scale}px ${16 * scale}px`,
                display: 'flex',
                alignItems: 'center',
                gap: 12 * scale,
              }}
            >
              <div style={{ color: COLORS.red400, fontWeight: 800, fontSize: 18 * scale }}>⚠</div>
              <span style={{ color: COLORS.white, fontSize: 14 * scale, fontWeight: 500, fontFamily: FONT.primary }}>
                Pacing is rushed under hard technical focus.
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* --- BOTTOM DESCRIPTION --- */}
      <div
        style={{
          position: 'absolute',
          bottom: 80 * scale,
          left: 80 * scale,
          right: 80 * scale,
          textAlign: 'center',
          opacity: titleOpacity,
          zIndex: 10,
        }}
      >
        <p style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, lineHeight: 1.6, maxWidth: 640 * scale, margin: '0 auto' }}>
          Get a comprehensive review of your conversational depth, verbal confidence, and STAR method delivery.
        </p>
      </div>
    </AbsoluteFill>
  );
};
