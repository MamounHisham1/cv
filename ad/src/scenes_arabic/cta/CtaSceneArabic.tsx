import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

const arabicStyle = `
  @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap');
  .arabic-text {
    font-family: 'Cairo', sans-serif !important;
    direction: rtl !important;
  }
`;

export const CtaSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Logo entrance (frame 5 to 25)
  const logoEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 80 } });
  const logoOpacity = logoEntrance;
  const logoScale = interpolate(logoEntrance, [0, 1], [0.8, 1]);

  // Free badge entrance (frame 20 to 40)
  const badgeEntrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 14, stiffness: 85 } });
  const badgeOpacity = badgeEntrance;
  const badgeScale = interpolate(badgeEntrance, [0, 1], [0.7, 1]);

  // Button entrance (frame 35 to 55)
  const btnEntrance = spring({ frame: Math.max(0, frame - 35), fps, config: { damping: 15, stiffness: 90 } });
  const btnOpacity = btnEntrance;
  const btnScale = interpolate(btnEntrance, [0, 1], [0.8, 1]);
  
  // Continuous breathing pulse for CTA button
  const buttonPulse = Math.sin(frame * 0.1) * 0.02 + 1;

  // URL display entrance (frame 50 to 70)
  const urlEntrance = spring({ frame: Math.max(0, frame - 50), fps, config: { damping: 16, stiffness: 80 } });
  const urlOpacity = urlEntrance;
  const urlY = interpolate(urlEntrance, [0, 1], [15 * scale, 0]);

  return (
    <AbsoluteFill className="arabic-text" style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden', padding: 80 * scale }}>
      <style dangerouslySetInnerHTML={{ __html: arabicStyle }} />
      {/* Drifting ambient background glows */}
      <GradientOrb x={150} y={300} size={450} color={COLORS.emerald500} opacity={0.08} speed={0.015} />
      <GradientOrb x={850} y={1300} size={380} color={COLORS.purple400} opacity={0.06} speed={0.02} />
      <GradientOrb x={540} y={800} size={300} color={COLORS.blue400} opacity={0.05} speed={0.025} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 50 * scale, zIndex: 1, position: 'relative', width: '100%' }}>
        
        {/* --- BRAND LOGO & TAGLINE --- */}
        <div style={{ opacity: logoOpacity, transform: `scale(${logoScale})`, display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <SeratyLogo size="large" showTagline={false} />
          <p style={{ color: COLORS.zinc400, fontSize: 18 * scale, fontWeight: 600, textAlign: 'center', marginTop: 12 * scale, textTransform: 'uppercase', letterSpacing: '0px' }}>
            محاكي مقابلات العمل بالذكاء الاصطناعي
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
          <div style={{ color: COLORS.white, fontSize: 34 * scale, fontWeight: 800, letterSpacing: '0px' }}>
            المقابلة الأولى مجانية
          </div>
          <div style={{ color: 'rgba(255,255,255,0.85)', fontSize: 16 * scale, fontWeight: 500, marginTop: 4 * scale }}>
            جرب مسؤول التوظيف دون أي تكلفة.
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
              fontSize: 22 * scale,
              fontWeight: 700,
              boxShadow: `0 0 40px ${COLORS.emerald500}25`,
              textAlign: 'center',
            }}
          >
            ابدأ المقابلة الصوتية
          </div>
          <span style={{ color: COLORS.zinc500, fontSize: 14 * scale, fontWeight: 500 }}>
            ٣٠ رصيداً مجانياً كل شهر • لا تتطلب بطاقة ائتمان
          </span>
        </div>

        {/* --- URL BOARD DISPLAY --- */}
        <div style={{ opacity: urlOpacity, transform: `translateY(${urlY}px)` }}>
          <div
            style={{
              color: COLORS.zinc300,
              fontSize: 22 * scale,
              fontWeight: 500,
              textAlign: 'center',
              padding: `${12 * scale}px ${28 * scale}px`,
              borderRadius: 12 * scale,
              background: COLORS.surface,
              border: `1px solid ${COLORS.border}`,
              letterSpacing: '0.5px',
            }}
          >
            seraty.softland.tech
          </div>
        </div>

      </div>
    </AbsoluteFill>
  );
};
