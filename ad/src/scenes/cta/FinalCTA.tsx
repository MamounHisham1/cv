import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FinalCTA: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const logoEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 80 } });

  const featuresOpacity = interpolate(frame, [20, 40], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const buttonScale = spring({ frame: Math.max(0, frame - 35), fps, config: { damping: 12, stiffness: 90 } });
  const buttonPulse = Math.sin(frame * 0.1) * 0.02 + 1;

  const urlOpacity = interpolate(frame, [50, 65], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
      <GradientOrb x={150} y={300} size={350} color={COLORS.emerald500} opacity={0.06} speed={0.015} />
      <GradientOrb x={850} y={1400} size={280} color={COLORS.purple400} opacity={0.04} speed={0.02} />
      <GradientOrb x={540} y={900} size={200} color={COLORS.blue400} opacity={0.04} speed={0.025} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 44 * scale, zIndex: 1, padding: 60 * scale }}>
        <div style={{ opacity: logoEntrance, transform: `scale(${logoEntrance})` }}>
          <SeratyLogo size="large" showTagline />
        </div>

        <div
          style={{
            opacity: featuresOpacity,
            display: 'flex',
            flexWrap: 'wrap',
            justifyContent: 'center',
            gap: 12 * scale,
            maxWidth: 800 * scale,
          }}
        >
          {[
            { label: 'AI-Powered Builder', color: COLORS.emerald400 },
            { label: '10 Templates', color: COLORS.blue400 },
            { label: 'ATS Optimized', color: COLORS.purple400 },
            { label: 'AI Interviewer', color: COLORS.emerald400 },
            { label: 'AI Evaluator', color: COLORS.amber400 },
          ].map((pill) => (
            <div
              key={pill.label}
              style={{
                padding: `${8 * scale}px ${18 * scale}px`,
                borderRadius: 20 * scale,
                background: `${pill.color}10`,
                border: `1px solid ${pill.color}20`,
                color: pill.color,
                fontSize: 13 * scale,
                fontWeight: 500,
                fontFamily: FONT.primary,
              }}
            >
              {pill.label}
            </div>
          ))}
        </div>

        <div
          style={{
            transform: `scale(${buttonScale * buttonPulse})`,
            opacity: buttonScale,
          }}
        >
          <div
            style={{
              background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald600})`,
              color: COLORS.white,
              padding: `${20 * scale}px ${52 * scale}px`,
              borderRadius: 16 * scale,
              fontSize: 22 * scale,
              fontWeight: 700,
              fontFamily: FONT.primary,
              boxShadow: `0 0 40px ${COLORS.emerald500}25`,
              textAlign: 'center',
            }}
          >
            Get Started Free
          </div>
          <div style={{ textAlign: 'center', marginTop: 12 * scale, color: COLORS.zinc500, fontSize: 13 * scale, fontFamily: FONT.primary }}>
            30 free credits • No credit card required
          </div>
        </div>

        <div style={{ opacity: urlOpacity }}>
          <div
            style={{
              color: COLORS.zinc300,
              fontSize: 20 * scale,
              fontWeight: 500,
              fontFamily: FONT.mono,
              textAlign: 'center',
              padding: `${12 * scale}px ${28 * scale}px`,
              borderRadius: 12 * scale,
              background: COLORS.surface,
              border: `1px solid ${COLORS.border}`,
            }}
          >
            seraty.softland.tech
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
