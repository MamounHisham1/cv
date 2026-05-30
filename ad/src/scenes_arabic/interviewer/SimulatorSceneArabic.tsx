import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

const arabicStyle = `
  @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap');
  .arabic-text {
    font-family: 'Cairo', sans-serif !important;
    direction: rtl !important;
  }
`;

export const SimulatorSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Title entrance (frame 5 to 25)
  const titleEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 90 } });
  const titleOpacity = titleEntrance;
  const titleY = interpolate(titleEntrance, [0, 1], [-20 * scale, 0]);

  // Orb entrance (frame 20 to 45)
  const orbEntrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 14, stiffness: 80 } });
  const orbOpacity = orbEntrance;
  const orbScale = interpolate(orbEntrance, [0, 1], [0.6, 1]);

  // Text entrance (frame 45 to 65)
  const textEntrance = spring({ frame: Math.max(0, frame - 45), fps, config: { damping: 16, stiffness: 85 } });
  const textOpacity = textEntrance;
  const textY = interpolate(textEntrance, [0, 1], [20 * scale, 0]);

  // Periodic breathing animation for the central voice orb
  const breathing = Math.sin(frame * 0.1) * 0.04 + 1;
  const outerBreathing = Math.sin(frame * 0.1 + Math.PI / 4) * 0.08 + 1;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'space-between', alignItems: 'center', overflow: 'hidden', padding: `${120 * scale}px ${80 * scale}px` }}>
      <style dangerouslySetInnerHTML={{ __html: arabicStyle }} />
      <OrbSet visible={true} />

      {/* --- TOP: Title Card --- */}
      <div className="arabic-text" style={{ opacity: titleOpacity, transform: `translateY(${titleY}px)`, textAlign: 'center', zIndex: 1, width: '100%' }}>
        <span style={{ color: COLORS.emerald400, fontSize: 14 * scale, fontWeight: 600, letterSpacing: '0px', display: 'block', marginBottom: 8 * scale }}>
          نقدّم لكم
        </span>
        <h2 style={{ color: COLORS.white, fontSize: 44 * scale, fontWeight: 800, lineHeight: 1.2, margin: 0 }}>
          محاكي المقابلات <br /> <span style={{ color: COLORS.emerald400 }}>بالذكاء الاصطناعي</span>
        </h2>
      </div>

      {/* --- CENTER: Glowing Neon Voice Orb --- */}
      <div style={{ position: 'relative', width: 300 * scale, height: 300 * scale, display: 'flex', alignItems: 'center', justifyContent: 'center', opacity: orbOpacity, transform: `scale(${orbScale})`, zIndex: 1 }}>
        {/* Outer glowing ripple ring */}
        <div
          style={{
            position: 'absolute',
            width: 260 * scale * outerBreathing,
            height: 260 * scale * outerBreathing,
            borderRadius: '50%',
            border: `1.5px dashed ${COLORS.emerald500}30`,
            boxShadow: `0 0 60px ${COLORS.emerald500}05`,
          }}
        />

        {/* Medium breathing halo */}
        <div
          style={{
            position: 'absolute',
            width: 200 * scale * breathing,
            height: 200 * scale * breathing,
            borderRadius: '50%',
            background: `radial-gradient(circle, ${COLORS.emerald500}10, transparent 70%)`,
            border: `1px solid ${COLORS.emerald500}20`,
            boxShadow: `0 0 80px ${COLORS.emerald500}15`,
          }}
        />

        {/* Inner solid glowing core */}
        <div
          style={{
            width: 120 * scale * breathing,
            height: 120 * scale * breathing,
            borderRadius: '50%',
            background: `linear-gradient(135deg, ${COLORS.emerald400}, ${COLORS.emerald600})`,
            boxShadow: `0 0 50px ${COLORS.emerald500}50, 0 0 100px ${COLORS.emerald500}25, inset 0 2px 4px rgba(255,255,255,0.3)`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
          }}
        >
          <svg width={48 * scale} height={48 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.bg} strokeWidth={2.5}>
            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" strokeLinecap="round" strokeLinejoin="round" />
            <path d="M19 10v1a7 7 0 01-14 0v-1M12 18v4M8 22h8" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
      </div>

      {/* --- BOTTOM: Tagline --- */}
      <div
        className="arabic-text"
        style={{
          opacity: textOpacity,
          transform: `translateY(${textY}px)`,
          textAlign: 'center',
          maxWidth: 720 * scale,
          zIndex: 1,
          marginBottom: 40 * scale,
          width: '100%',
        }}
      >
        <p style={{ color: COLORS.zinc300, fontSize: 22 * scale, fontWeight: 500, lineHeight: 1.5, margin: 0 }}>
          مسؤول توظيف تفاعلي بالصوت يقيّم أدائك
          <br />
          تماماً كالبشر في مقابلات حية.
        </p>
      </div>
    </AbsoluteFill>
  );
};
