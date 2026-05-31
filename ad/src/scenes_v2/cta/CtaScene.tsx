import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';
import { COLORS, FONT } from '../../styles';

export const CtaScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const logoEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 80 } });
  const logoOpacity = interpolate(logoEntrance, [0, 1], [0, 1]);
  const logoScale = interpolate(logoEntrance, [0, 1], [0.8, 1]);

  const badgeEntrance = spring({ frame: Math.max(0, frame - 35), fps, config: { damping: 14, stiffness: 85 } });
  const badgeOpacity = interpolate(badgeEntrance, [0, 1], [0, 1]);
  const badgeScale = interpolate(badgeEntrance, [0, 1], [0.7, 1]);

  const btnEntrance = spring({ frame: Math.max(0, frame - 65), fps, config: { damping: 15, stiffness: 90 } });
  const btnOpacity = interpolate(btnEntrance, [0, 1], [0, 1]);
  const btnScale = interpolate(btnEntrance, [0, 1], [0.85, 1]);

  const buttonPulse = Math.sin(frame * 0.1) * 0.02 + 1;

  const urlFade = interpolate(frame, [95, 135], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const taglineFade = interpolate(frame, [135, 175], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill
      style={{
        backgroundColor: COLORS.bg,
        justifyContent: 'center',
        alignItems: 'center',
        overflow: 'hidden',
        padding: 60,
      }}
    >
      <GradientOrb x={150} y={300} size={550} color={COLORS.emerald500} opacity={0.06} speed={0.015} offset={0} />
      <GradientOrb x={900} y={1400} size={450} color={COLORS.purple400} opacity={0.04} speed={0.02} offset={60} />
      <GradientOrb x={540} y={960} size={400} color={COLORS.blue400} opacity={0.03} speed={0.025} offset={120} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48, zIndex: 1 }}>
        <div style={{ opacity: logoOpacity, transform: `scale(${logoScale})` }}>
          <SeratyLogo size="large" showTagline={false} />
        </div>

        <div
          style={{
            opacity: badgeOpacity,
            transform: `scale(${badgeScale})`,
            background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald700})`,
            borderRadius: 32,
            padding: '32px 56px',
            boxShadow: `0 0 60px ${COLORS.emerald500}25, 0 0 120px ${COLORS.emerald500}10`,
            textAlign: 'center',
            width: '85%',
            maxWidth: 700,
          }}
        >
          <div style={{ color: COLORS.white, fontSize: 52, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: -1 }}>
            First Interview Free
          </div>
          <div style={{ color: 'rgba(255,255,255,0.85)', fontSize: 24, fontWeight: 500, fontFamily: FONT.primary, marginTop: 8 }}>
            No credit card required. Start today.
          </div>
        </div>

        <div
          style={{
            opacity: btnOpacity,
            transform: `scale(${btnScale * buttonPulse})`,
            background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald600})`,
            color: COLORS.white,
            padding: '24px 64px',
            borderRadius: 20,
            fontSize: 32,
            fontWeight: 700,
            fontFamily: FONT.primary,
            boxShadow: `0 0 50px ${COLORS.emerald500}25`,
            letterSpacing: -0.5,
          }}
        >
          Try SeratyAI Free
        </div>

        <div style={{ opacity: urlFade }}>
          <div
            style={{
              color: COLORS.zinc400,
              fontSize: 26,
              fontWeight: 500,
              fontFamily: FONT.mono,
              textAlign: 'center',
              padding: '14px 32px',
              borderRadius: 14,
              background: COLORS.surface,
              border: `1px solid ${COLORS.border}`,
            }}
          >
            seraty.softland.tech
          </div>
        </div>

        <div
          style={{
            opacity: taglineFade,
            fontSize: 22,
            fontWeight: 500,
            fontFamily: FONT.primary,
            color: COLORS.zinc600,
            textAlign: 'center',
            letterSpacing: '0.05em',
          }}
        >
          Build. Enhance. Evaluate. &mdash; All with AI
        </div>
      </div>
    </AbsoluteFill>
  );
};
