import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const SeratyTransition: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const logoScale = spring({ frame: Math.max(0, frame - 8), fps, config: { damping: 14, stiffness: 80 } });

  const wipeProgress = interpolate(frame, [0, 20], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const taglineOpacity = interpolate(frame, [20, 35], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
      <GradientOrb x={200} y={400} size={300} color={COLORS.emerald500} opacity={0.08} speed={0.02} />
      <GradientOrb x={800} y={1200} size={250} color={COLORS.purple400} opacity={0.06} speed={0.03} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 32 * scale, zIndex: 1 }}>
        <div style={{ transform: `scale(${logoScale})`, opacity: logoScale }}>
          <SeratyLogo size="large" />
        </div>

        <div
          style={{
            opacity: taglineOpacity,
            height: 2 * scale,
            width: interpolate(taglineOpacity, [0, 1], [0, 200]) * scale,
            background: `linear-gradient(90deg, transparent, ${COLORS.emerald500}, transparent)`,
          }}
        />

        <div style={{ opacity: taglineOpacity, textAlign: 'center' }}>
          <div style={{ color: COLORS.white, fontSize: 22 * scale, fontWeight: 600, fontFamily: FONT.primary }}>
            More than just interviews
          </div>
          <div style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, marginTop: 8 * scale }}>
            Your complete AI-powered career toolkit
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
