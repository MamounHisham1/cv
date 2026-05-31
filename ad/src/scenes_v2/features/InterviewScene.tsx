import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

export const InterviewScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const orbEntrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 16, stiffness: 80 } });
  const orbOpacity = interpolate(orbEntrance, [0, 1], [0, 1]);
  const orbScale = interpolate(orbEntrance, [0, 1], [0.6, 1]);

  const recruiterCardEntrance = spring({ frame: Math.max(0, frame - 100), fps, config: { damping: 14, stiffness: 90 } });
  const recruiterCardOpacity = interpolate(recruiterCardEntrance, [0, 1], [0, 1]);
  const recruiterCardY = interpolate(recruiterCardEntrance, [0, 1], [40, 0]);

  const typingDots = frame % 40 < 20;
  const seekerThinking = frame >= 300 && frame < 400;

  const seekerCardEntrance = spring({ frame: Math.max(0, frame - 350), fps, config: { damping: 14, stiffness: 90 } });
  const seekerCardOpacity = interpolate(seekerCardEntrance, [0, 1], [0, 1]);
  const seekerCardY = interpolate(seekerCardEntrance, [0, 1], [40, 0]);

  const feedback1Entrance = spring({ frame: Math.max(0, frame - 510), fps, config: { damping: 14, stiffness: 100 } });
  const feedback2Entrance = spring({ frame: Math.max(0, frame - 540), fps, config: { damping: 14, stiffness: 100 } });
  const feedback3Entrance = spring({ frame: Math.max(0, frame - 570), fps, config: { damping: 14, stiffness: 100 } });

  const breathing = Math.sin(frame * 0.08) * 0.04 + 1;
  const outerBreathing = Math.sin(frame * 0.08 + Math.PI / 4) * 0.06 + 1;

  const narratorFade = interpolate(frame, [5, 25], [1, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill
      style={{
        backgroundColor: COLORS.bg,
        justifyContent: 'center',
        alignItems: 'center',
        overflow: 'hidden',
        padding: '100px 50px',
      }}
    >
      <GradientOrb x={200} y={400} size={500} color={COLORS.emerald500} opacity={0.08} speed={0.015} offset={0} />
      <GradientOrb x={900} y={1400} size={400} color={COLORS.purple400} opacity={0.05} speed={0.02} offset={60} />

      {/* Header label */}
      <div style={{ opacity: orbOpacity, position: 'absolute', top: 60, zIndex: 1 }}>
        <span
          style={{
            fontSize: 24,
            fontWeight: 700,
            fontFamily: FONT.primary,
            color: COLORS.emerald400,
            letterSpacing: '0.12em',
            textTransform: 'uppercase',
          }}
        >
          AI Mock Interview
        </span>
      </div>

      {/* Narrator label (fades out) */}
      {frame < 60 && (
        <div
          style={{
            opacity: narratorFade,
            position: 'absolute',
            top: 110,
            fontSize: 32,
            fontWeight: 400,
            fontFamily: FONT.primary,
            color: COLORS.zinc500,
            textAlign: 'center',
            zIndex: 1,
          }}
        >
          Here's how it works
        </div>
      )}

      {/* AI Orb — always visible */}
      <div
        style={{
          opacity: orbOpacity,
          transform: `scale(${orbScale})`,
          position: 'relative',
          width: 160,
          height: 160,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          zIndex: 1,
          marginBottom: 30,
        }}
      >
        <div
          style={{
            position: 'absolute',
            width: 160 * outerBreathing,
            height: 160 * outerBreathing,
            borderRadius: '50%',
            border: `1.5px dashed ${COLORS.emerald500}20`,
            boxShadow: `0 0 40px ${COLORS.emerald500}05`,
          }}
        />
        <div
          style={{
            position: 'absolute',
            width: 130 * breathing,
            height: 130 * breathing,
            borderRadius: '50%',
            background: `radial-gradient(circle, ${COLORS.emerald500}12, transparent 70%)`,
            border: `1px solid ${COLORS.emerald500}15`,
          }}
        />
        <div
          style={{
            width: 75 * breathing,
            height: 75 * breathing,
            borderRadius: '50%',
            background: `linear-gradient(135deg, ${COLORS.emerald400}, ${COLORS.emerald600})`,
            boxShadow: `0 0 50px ${COLORS.emerald500}40, 0 0 100px ${COLORS.emerald500}15`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
          }}
        >
          <svg width={32} height={32} viewBox="0 0 24 24" fill="none" stroke={COLORS.bg} strokeWidth={2.5}>
            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M19 10v1a7 7 0 01-14 0v-1M12 18v4M8 22h8" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
      </div>

      {/* AI Recruiter question card */}
      <div
        style={{
          opacity: recruiterCardOpacity,
          transform: `translateY(${recruiterCardY}px)`,
          background: `linear-gradient(135deg, ${COLORS.zinc900}, ${COLORS.bgLight})`,
          borderRadius: 24,
          border: '1px solid rgba(16, 185, 129, 0.3)',
          padding: '28px 36px',
          maxWidth: 800,
          width: '100%',
          zIndex: 1,
          marginBottom: 20,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 14, marginBottom: 14 }}>
          <div
            style={{
              width: 40,
              height: 40,
              borderRadius: '50%',
              background: `linear-gradient(135deg, ${COLORS.emerald400}, ${COLORS.emerald600})`,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontSize: 18,
              fontWeight: 700,
              color: COLORS.white,
              fontFamily: FONT.primary,
            }}
          >
            AI
          </div>
          <span style={{ fontSize: 18, fontWeight: 600, fontFamily: FONT.primary, color: COLORS.emerald400 }}>
            AI Recruiter
          </span>
          <span style={{ fontSize: 14, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc600, marginLeft: 'auto' }}>
            {frame < 300 ? 'Speaking...' : ''}
          </span>
        </div>
        <div style={{ fontSize: 28, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc100, lineHeight: 1.4 }}>
          &ldquo;Tell me about a time you led a team through a difficult project. What was your approach?&rdquo;
        </div>
        {/* Voice waveform indicator */}
        {frame >= 130 && frame < 330 && (
          <div style={{ display: 'flex', gap: 3, marginTop: 16, alignItems: 'center' }}>
            {[0.3, 0.6, 0.9, 0.5, 0.8, 1.0, 0.7, 0.4].map((h, i) => (
              <div
                key={i}
                style={{
                  width: 4,
                  height: 14 * h * (0.7 + Math.sin(frame * 0.2 + i) * 0.3),
                  borderRadius: 2,
                  background: COLORS.emerald400,
                  opacity: 0.6,
                }}
              />
            ))}
          </div>
        )}
      </div>

      {/* Thinking indicator */}
      {seekerThinking && (
        <div
          style={{
            opacity: interpolate(frame, [300, 320], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
            display: 'flex',
            alignItems: 'center',
            gap: 12,
            padding: '16px 24px',
            zIndex: 1,
            marginBottom: 10,
          }}
        >
          <div style={{ fontSize: 28, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc500 }}>
            Thinking
          </div>
          <div style={{ display: 'flex', gap: 6 }}>
            {[0, 1, 2].map((i) => (
              <div
                key={i}
                style={{
                  width: 10,
                  height: 10,
                  borderRadius: '50%',
                  background: COLORS.zinc500,
                  opacity: typingDots ? 0.3 + i * 0.3 : 0.3,
                  transition: 'opacity 0.2s',
                }}
              />
            ))}
          </div>
        </div>
      )}

      {/* Job Seeker answer card */}
      <div
        style={{
          opacity: seekerCardOpacity,
          transform: `translateY(${seekerCardY}px)`,
          background: `linear-gradient(135deg, ${COLORS.zinc900}, #1a1a2e)`,
          borderRadius: 24,
          border: '1px solid rgba(96, 165, 250, 0.25)',
          padding: '28px 36px',
          maxWidth: 800,
          width: '100%',
          zIndex: 1,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 14, marginBottom: 14 }}>
          <div
            style={{
              width: 40,
              height: 40,
              borderRadius: '50%',
              background: COLORS.zinc600,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontSize: 18,
              fontWeight: 700,
              color: COLORS.white,
              fontFamily: FONT.primary,
            }}
          >
            Y
          </div>
          <span style={{ fontSize: 18, fontWeight: 600, fontFamily: FONT.primary, color: COLORS.blue400 }}>
            Job Seeker
          </span>
        </div>
        <div style={{ fontSize: 26, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc100, lineHeight: 1.5 }}>
          &ldquo;I led a team of five designers during a major product launch. We were behind schedule, so I reorganised our workflow and prioritised critical features. We delivered on time with a 20% increase in user engagement.&rdquo;
        </div>
        {/* Voice waveform indicator */}
        {frame >= 400 && frame < 650 && (
          <div style={{ display: 'flex', gap: 3, marginTop: 16, alignItems: 'center' }}>
            {[0.3, 0.6, 0.9, 0.5, 0.8, 1.0, 0.7, 0.4].map((h, i) => (
              <div
                key={i}
                style={{
                  width: 4,
                  height: 14 * h * (0.7 + Math.sin(frame * 0.2 + i + 3) * 0.3),
                  borderRadius: 2,
                  background: COLORS.blue400,
                  opacity: 0.6,
                }}
              />
            ))}
          </div>
        )}
      </div>

      {/* Feedback badges at bottom */}
      <div
        style={{
          display: 'flex',
          gap: 20,
          zIndex: 1,
          flexWrap: 'wrap',
          justifyContent: 'center',
          marginTop: 30,
        }}
      >
        <div
          style={{
            opacity: interpolate(feedback1Entrance, [0, 1], [0, 1]),
            transform: `translateY(${interpolate(feedback1Entrance, [0, 1], [20, 0])}px)`,
            background: 'rgba(16, 185, 129, 0.1)',
            border: '1px solid rgba(16, 185, 129, 0.2)',
            borderRadius: 16,
            padding: '14px 24px',
            display: 'flex',
            alignItems: 'center',
            gap: 12,
          }}
        >
          <span style={{ color: COLORS.emerald400, fontSize: 28 }}>✓</span>
          <div>
            <div style={{ fontSize: 28, fontWeight: 800, fontFamily: FONT.primary, color: COLORS.white }}>92%</div>
            <div style={{ fontSize: 16, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc500 }}>Clarity</div>
          </div>
        </div>

        <div
          style={{
            opacity: interpolate(feedback2Entrance, [0, 1], [0, 1]),
            transform: `translateY(${interpolate(feedback2Entrance, [0, 1], [20, 0])}px)`,
            background: 'rgba(16, 185, 129, 0.1)',
            border: '1px solid rgba(16, 185, 129, 0.2)',
            borderRadius: 16,
            padding: '14px 24px',
            display: 'flex',
            alignItems: 'center',
            gap: 12,
          }}
        >
          <span style={{ color: COLORS.emerald400, fontSize: 28 }}>✓</span>
          <div>
            <div style={{ fontSize: 28, fontWeight: 800, fontFamily: FONT.primary, color: COLORS.white }}>85%</div>
            <div style={{ fontSize: 16, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc500 }}>Confidence</div>
          </div>
        </div>

        <div
          style={{
            opacity: interpolate(feedback3Entrance, [0, 1], [0, 1]),
            transform: `translateY(${interpolate(feedback3Entrance, [0, 1], [20, 0])}px)`,
            background: 'rgba(251, 191, 36, 0.1)',
            border: '1px solid rgba(251, 191, 36, 0.2)',
            borderRadius: 16,
            padding: '14px 24px',
            display: 'flex',
            alignItems: 'center',
            gap: 12,
          }}
        >
          <span style={{ color: COLORS.amber400, fontSize: 28 }}>→</span>
          <div>
            <div style={{ fontSize: 28, fontWeight: 800, fontFamily: FONT.primary, color: COLORS.white }}>75%</div>
            <div style={{ fontSize: 16, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc500 }}>Structure</div>
          </div>
        </div>
      </div>

      {/* Real-time feedback label */}
      <div
        style={{
          opacity: interpolate(feedback1Entrance, [0, 1], [0, 1]),
          marginTop: 24,
          fontSize: 24,
          fontWeight: 400,
          fontFamily: FONT.primary,
          color: COLORS.zinc500,
          textAlign: 'center',
          letterSpacing: '0.03em',
        }}
      >
        Real-time feedback on every answer
      </div>
    </AbsoluteFill>
  );
};


