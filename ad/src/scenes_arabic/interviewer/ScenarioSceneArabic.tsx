import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, LAYOUT } from '../../styles';

const arabicStyle = `
  @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap');
  .arabic-text {
    font-family: 'Cairo', sans-serif !important;
    direction: rtl !important;
    text-align: center !important;
  }
`;

export const ScenarioSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Slide in "غداً هو..." from frame 10 to 30
  const line1Entrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 16, stiffness: 90 } });
  const line1Opacity = line1Entrance;
  const line1Y = interpolate(line1Entrance, [0, 1], [-30 * scale, 0]);

  // Slide in "مقابلتك الشخصية الكبرى." from frame 22 to 42
  const line2Entrance = spring({ frame: Math.max(0, frame - 22), fps, config: { damping: 16, stiffness: 90 } });
  const line2Opacity = line2Entrance;
  const line2Y = interpolate(line2Entrance, [0, 1], [20 * scale, 0]);

  // Slide in "هل أنت مستعد حقاً؟" from frame 50 to 70
  const line3Entrance = spring({ frame: Math.max(0, frame - 50), fps, config: { damping: 14, stiffness: 80 } });
  const line3Opacity = line3Entrance;
  const line3Y = interpolate(line3Entrance, [0, 1], [30 * scale, 0]);

  // Breathing heartbeat pulse starting at frame 65
  const pulse = frame >= 65 ? Math.sin((frame - 65) * 0.15) * 0.03 + 1 : 1;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden', padding: 80 * scale }}>
      <style dangerouslySetInnerHTML={{ __html: arabicStyle }} />
      <OrbSet visible={true} />

      <div className="arabic-text" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 20 * scale, zIndex: 1, width: '100%' }}>
        {/* tomorrow is... */}
        <div
          style={{
            opacity: line1Opacity,
            transform: `translateY(${line1Y}px)`,
            color: COLORS.zinc400,
            fontSize: 34 * scale,
            fontWeight: 500,
            letterSpacing: '0px',
          }}
        >
          غداً هو...
        </div>
        
        {/* your big job interview */}
        <div
          style={{
            opacity: line2Opacity,
            transform: `translateY(${line2Y}px)`,
            color: COLORS.white,
            fontSize: 58 * scale,
            fontWeight: 800,
            lineHeight: 1.2,
          }}
        >
          مقابلتك <span style={{ color: COLORS.emerald400 }}>الشخصية الكبرى</span>.
        </div>
        
        {/* Are you actually ready? */}
        <div
          style={{
            opacity: line3Opacity,
            transform: `scale(${line3Entrance * pulse}) translateY(${line3Y}px)`,
            marginTop: 48 * scale,
          }}
        >
          <div
            style={{
              color: COLORS.red400,
              fontSize: 44 * scale,
              fontWeight: 800,
              textShadow: `0 0 40px ${COLORS.red500}30`,
            }}
          >
            هل أنت مستعد حقاً؟
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
