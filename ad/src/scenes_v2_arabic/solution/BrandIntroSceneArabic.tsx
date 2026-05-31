import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS } from '../../styles';
import { SeratyLogo } from '../../components/SeratyLogo';
import { GradientOrb } from '../../components/GradientOrb';

const ARABIC_FONT = "'Cairo', 'Tajawal', sans-serif";

export const BrandIntroSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const logoEntrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 16, stiffness: 80 } });
  const logoOpacity = interpolate(logoEntrance, [0, 1], [0, 1]);
  const logoScale = interpolate(logoEntrance, [0, 1], [0.7, 1]);

  const tagline1 = interpolate(frame, [55, 80], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const tagline2 = interpolate(frame, [90, 115], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const tagline3 = interpolate(frame, [125, 150], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const subtextFade = interpolate(frame, [160, 195], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

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
      <GradientOrb x={540} y={960} size={800} color={COLORS.emerald500} opacity={0.12} speed={0.015} offset={0} />
      <GradientOrb x={300} y={600} size={500} color={COLORS.blue400} opacity={0.06} speed={0.02} offset={100} />
      <GradientOrb x={800} y={1400} size={450} color={COLORS.purple400} opacity={0.05} speed={0.018} offset={200} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48, zIndex: 1 }}>
        <div style={{ opacity: logoOpacity, transform: `scale(${logoScale})` }}>
          <SeratyLogo size="large" showTagline={false} />
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16 }}>
          <div
            style={{
              opacity: tagline1,
              transform: `translateY(${interpolate(tagline1, [0, 1], [20, 0])}px)`,
              fontSize: 48,
              fontWeight: 600,
              fontFamily: ARABIC_FONT,
              color: COLORS.zinc300,
              letterSpacing: -1,
              direction: 'rtl',
            }}
          >
            ابنِ
          </div>
          <div
            style={{
              opacity: tagline2,
              transform: `translateY(${interpolate(tagline2, [0, 1], [20, 0])}px)`,
              fontSize: 48,
              fontWeight: 600,
              fontFamily: ARABIC_FONT,
              color: COLORS.zinc300,
              letterSpacing: -1,
              direction: 'rtl',
            }}
          >
            طور
          </div>
          <div
            style={{
              opacity: tagline3,
              transform: `translateY(${interpolate(tagline3, [0, 1], [20, 0])}px)`,
              fontSize: 48,
              fontWeight: 600,
              fontFamily: ARABIC_FONT,
              color: COLORS.emerald400,
              letterSpacing: -1,
              direction: 'rtl',
            }}
          >
            تقييم
          </div>
        </div>

        <div
          style={{
            opacity: subtextFade,
            marginTop: 24,
            fontSize: 28,
            fontWeight: 500,
            fontFamily: ARABIC_FONT,
            color: COLORS.zinc500,
            textAlign: 'center',
            letterSpacing: '0.08em',
            direction: 'rtl',
          }}
        >
          كل ده بالذكاء الاصطناعي
        </div>
      </div>
    </AbsoluteFill>
  );
};
