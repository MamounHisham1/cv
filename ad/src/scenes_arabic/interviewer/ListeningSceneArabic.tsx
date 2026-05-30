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

export const ListeningSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Title entrance (frame 5 to 25)
  const titleEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 90 } });
  const titleOpacity = titleEntrance;
  const titleY = interpolate(titleEntrance, [0, 1], [-20 * scale, 0]);

  // Wave entrance (frame 20 to 45)
  const waveEntrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 14, stiffness: 80 } });
  const waveOpacity = waveEntrance;
  const waveScale = interpolate(waveEntrance, [0, 1], [0.8, 1]);

  // Dynamic voice wave lines (represents user answering)
  const barCount = 18;
  const bars = [];
  
  for (let i = 0; i < barCount; i++) {
    const timeVal = frame / 30;
    const phaseOffset = i * 0.25;
    const waveHeight = 10 * scale + 160 * scale * Math.pow(Math.max(0, Math.sin(timeVal * 6 + phaseOffset * 2) * Math.cos(timeVal * 3 - phaseOffset)), 1.5) * waveEntrance;
    
    // Smooth gradient color from purple to emerald
    const factor = i / (barCount - 1);
    const color = factor < 0.5 
      ? interpolateColor(factor * 2, COLORS.purple400, COLORS.emerald400)
      : interpolateColor((factor - 0.5) * 2, COLORS.emerald400, COLORS.blue400);

    bars.push(
      <div
        key={i}
        style={{
          width: 14 * scale,
          height: waveHeight,
          borderRadius: 8 * scale,
          backgroundColor: color,
          boxShadow: `0 0 16px ${color}35`,
          transition: 'height 0.05s ease-out',
        }}
      />
    );
  }

  // Helper function to blend colors in JS/React inline styles
  function interpolateColor(factor: number, c1: string, c2: string): string {
    const hex = (x: string) => parseInt(x.replace('#', ''), 16);
    const r1 = (hex(c1) >> 16) & 255;
    const g1 = (hex(c1) >> 8) & 255;
    const b1 = hex(c1) & 255;
    const r2 = (hex(c2) >> 16) & 255;
    const g2 = (hex(c2) >> 8) & 255;
    const b2 = hex(c2) & 255;
    
    const r = Math.round(r1 + factor * (r2 - r1));
    const g = Math.round(g1 + factor * (g2 - g1));
    const b = Math.round(b1 + factor * (b2 - b1));
    
    return `rgb(${r}, ${g}, ${b})`;
  }

  // Listening badge pulsing
  const pulse = Math.sin(frame * 0.2) * 0.03 + 1;

  return (
    <AbsoluteFill className="arabic-text" style={{ backgroundColor: COLORS.bg, justifyContent: 'space-between', alignItems: 'center', overflow: 'hidden', padding: `${130 * scale}px ${80 * scale}px` }}>
      <style dangerouslySetInnerHTML={{ __html: arabicStyle }} />
      <OrbSet visible={true} />

      {/* --- TOP: Title --- */}
      <div style={{ opacity: titleOpacity, transform: `translateY(${titleY}px)`, textAlign: 'center', zIndex: 1, width: '100%' }}>
        <span style={{ color: COLORS.emerald400, fontSize: 14 * scale, fontWeight: 600, display: 'block', marginBottom: 8 * scale }}>
          تقنية صوتية متقدمة
        </span>
        <h2 style={{ color: COLORS.white, fontSize: 40 * scale, fontWeight: 800, letterSpacing: '-1px', lineHeight: 1.2, margin: 0 }}>
          لا كتابة بعد اليوم. <br /> تحدث بطبيعتك فقط.
        </h2>
      </div>

      {/* --- CENTER: Glowing Spoken Waveform --- */}
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 36 * scale, opacity: waveOpacity, transform: `scale(${waveScale})`, zIndex: 1 }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 10 * scale, height: 260 * scale, width: LAYOUT.width - 240 * scale }}>
          {bars}
        </div>

        {/* Listening Active Badge */}
        <div
          style={{
            transform: `scale(${pulse})`,
            display: 'flex',
            alignItems: 'center',
            gap: 10 * scale,
            background: 'rgba(255,255,255,0.04)',
            border: `1.5px solid ${COLORS.border}`,
            borderRadius: 30 * scale,
            padding: `${10 * scale}px ${20 * scale}px`,
          }}
        >
          <div style={{ position: 'relative', width: 10 * scale, height: 10 * scale }}>
            <div style={{ position: 'absolute', inset: 0, borderRadius: '50%', backgroundColor: COLORS.purple400, transform: 'scale(1.6)', opacity: 0.3 }} />
            <div style={{ position: 'absolute', inset: 0, borderRadius: '50%', backgroundColor: COLORS.purple400 }} />
          </div>
          <span style={{ color: COLORS.white, fontSize: 14 * scale, fontWeight: 600 }}>
            المستخدم يتحدث...
          </span>
        </div>
      </div>

      {/* --- BOTTOM DESCRIPTION --- */}
      <div
        style={{
          opacity: titleOpacity,
          textAlign: 'center',
          maxWidth: 720 * scale,
          zIndex: 1,
          marginBottom: 30 * scale,
        }}
      >
        <p style={{ color: COLORS.zinc400, fontSize: 18 * scale, lineHeight: 1.6, margin: 0 }}>
          نموذجنا الحواري المتقدم يقوم بنسخ، وتفسير،
          <br />
          وتقييم إجابتك الصوتية بشكل فوري وتفاعلي.
        </p>
      </div>
    </AbsoluteFill>
  );
};
