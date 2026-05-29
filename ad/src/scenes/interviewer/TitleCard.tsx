import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const TitleCard: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const bgGlow = interpolate(frame, [0, 30], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const titleSpring = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 14, stiffness: 90 } });
  const taglineOpacity = interpolate(frame, [15, 30], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div
        style={{
          position: 'absolute',
          width: 400 * scale,
          height: 400 * scale,
          borderRadius: '50%',
          background: `radial-gradient(circle, ${COLORS.emerald500}${Math.round(bgGlow * 15).toString(16).padStart(2, '0')}, transparent 70%)`,
          filter: 'blur(60px)',
        }}
      />
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 32 * scale, zIndex: 1 }}>
        <div style={{ transform: `scale(${titleSpring})`, display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <div style={{ fontSize: 20 * scale, color: COLORS.emerald400, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.2em', textTransform: 'uppercase', marginBottom: 16 * scale }}>
            Introducing
          </div>
          <div style={{ color: COLORS.white, fontSize: 64 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-2.5px' }}>
            Seraty<span style={{ color: COLORS.emerald400 }}>AI</span>
          </div>
          <div style={{ color: COLORS.white, fontSize: 48 * scale, fontWeight: 700, fontFamily: FONT.primary, letterSpacing: '-1.5px', marginTop: 8 * scale }}>
            Interviewer
          </div>
        </div>
        <div
          style={{
            opacity: taglineOpacity,
            height: 2 * scale,
            width: 200 * scale,
            background: `linear-gradient(90deg, transparent, ${COLORS.emerald500}, transparent)`,
          }}
        />
        <div style={{ opacity: taglineOpacity, color: COLORS.zinc400, fontSize: 20 * scale, fontFamily: FONT.primary, textAlign: 'center', lineHeight: 1.6 }}>
          Voice-based mock interviews
          <br />
          powered by AI
        </div>
      </div>
    </AbsoluteFill>
  );
};
