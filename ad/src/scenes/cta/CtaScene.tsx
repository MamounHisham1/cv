import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const CtaScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Logo entrance (frame 5 to 25)
  const logoEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 80 } });
  const logoOpacity = logoEntrance;
  const logoScale = interpolate(logoEntrance, [0, 1], [0.8, 1]);

  // Free badge entrance (frame 25 to 50)
  const badgeEntrance = spring({ frame: Math.max(0, frame - 25), fps, config: { damping: 14, stiffness: 85 } });
  const badgeOpacity = badgeEntrance;
  const badgeScale = interpolate(badgeEntrance, [0, 1], [0.7, 1]);

  // Button entrance (frame 45 to 70)
  const btnEntrance = spring({ frame: Math.max(0, frame - 45), fps, config: { damping: 15, stiffness: 90 } });
  const btnOpacity = btnEntrance;
  const btnScale = interpolate(btnEntrance, [0, 1], [0.8, 1]);
  
  // Continuous breathing pulse for CTA button
  const buttonPulse = Math.sin(frame * 0.1) * 0.02 + 1;

  // URL display entrance (frame 65 to 85)
  const urlEntrance = spring({ frame: Math.max(0, frame - 65), fps, config: { damping: 16, stiffness: 80 } });
  const urlOpacity = urlEntrance;
  const urlY = interpolate(urlEntrance, [0, 1], [15 * scale, 0]);

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden', padding: 80 * scale }}>
      {/* Drifting premium ambient background glows */}
      <GradientOrb x={150} y={300} size={450} color={COLORS.emerald500} opacity={0.08} speed={0.015} />
      <GradientOrb x={850} y={1300} size={380} color={COLORS.purple400} opacity={0.06} speed={0.02} />
      <GradientOrb x={540} y={800} size={300} color={COLORS.blue400} opacity={0.05} speed={0.025} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 50 * scale, zIndex: 1, position: 'relative' }}>
        
        {/* --- BRAND LOGO & TAGLINE --- */}
        <div style={{ opacity: logoOpacity, transform: `scale(${logoScale})` }}>
          <SeratyLogo size="large" showTagline={false} />
          <p style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, textAlign: 'center', marginTop: 12 * scale, letterSpacing: '0.05em', textTransform: 'uppercase' }}>
            AI Mock Interview Simulator
          </p>
        </div>

        {/* --- THE FREE OFFER BADGE --- */}
        <div
          style={{
            opacity: badgeOpacity,
            transform: `scale(${badgeScale})`,
            background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald700})`,
            borderRadius: 24 * scale,
            padding: `${24 * scale}px ${44 * scale}px`,
            boxShadow: `0 0 50px ${COLORS.emerald500}30, 0 0 100px ${COLORS.emerald500}15`,
            textAlign: 'center',
            width: LAYOUT.width - 240 * scale,
          }}
        >
          <div style={{ color: COLORS.white, fontSize: 34 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-0.5px' }}>
            First Interview Free
          </div>
          <div style={{ color: 'rgba(255,255,255,0.85)', fontSize: 15 * scale, fontWeight: 500, fontFamily: FONT.primary, marginTop: 4 * scale }}>
            Try the AI Recruiter at no cost.
          </div>
        </div>

        {/* --- PULSING ACTION BUTTON --- */}
        <div
          style={{
            opacity: btnOpacity,
            transform: `scale(${btnScale * buttonPulse})`,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            gap: 12 * scale,
          }}
        >
          <div
            style={{
              background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald600})`,
              color: COLORS.white,
              padding: `${20 * scale}px ${48 * scale}px`,
              borderRadius: 16 * scale,
              fontSize: 20 * scale,
              fontWeight: 700,
              fontFamily: FONT.primary,
              boxShadow: `0 0 40px ${COLORS.emerald500}25`,
              textAlign: 'center',
            }}
          >
            Start Voice Interview
          </div>
          <span style={{ color: COLORS.zinc500, fontSize: 13 * scale, fontFamily: FONT.primary }}>
            30 Free Credits Every Month • No Card Required
          </span>
        </div>

        {/* --- URL BOARD DISPLAY --- */}
        <div style={{ opacity: urlOpacity, transform: `translateY(${urlY})` }}>
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
