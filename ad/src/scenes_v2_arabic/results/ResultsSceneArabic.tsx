import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

const ARABIC_FONT = "'Cairo', 'Tajawal', sans-serif";

export const ResultsSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const phoneEntrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 16, stiffness: 90 } });
  const phoneOpacity = interpolate(phoneEntrance, [0, 1], [0, 1]);
  const phoneScale = interpolate(phoneEntrance, [0, 1], [0.7, 1]);

  const notificationEntrance = spring({ frame: Math.max(0, frame - 50), fps, config: { damping: 14, stiffness: 85 } });
  const notificationY = interpolate(notificationEntrance, [0, 1], [80, 0]);
  const notificationOpacity = interpolate(notificationEntrance, [0, 1], [0, 1]);

  const statEntrance = spring({ frame: Math.max(0, frame - 110), fps, config: { damping: 14, stiffness: 80 } });
  const statOpacity = interpolate(statEntrance, [0, 1], [0, 1]);
  const statScale = interpolate(statEntrance, [0, 1], [0.8, 1]);

  const quoteEntrance = spring({ frame: Math.max(0, frame - 165), fps, config: { damping: 16, stiffness: 85 } });
  const quoteOpacity = interpolate(quoteEntrance, [0, 1], [0, 1]);
  const quoteY = interpolate(quoteEntrance, [0, 1], [25, 0]);

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
      <GradientOrb x={540} y={960} size={600} color={COLORS.emerald500} opacity={0.1} speed={0.015} offset={0} />
      <GradientOrb x={200} y={400} size={400} color={COLORS.blue400} opacity={0.06} speed={0.02} offset={50} />

      <div
        style={{
          opacity: phoneOpacity,
          transform: `scale(${phoneScale})`,
          width: 320,
          height: 580,
          background: `linear-gradient(180deg, ${COLORS.zinc900}, ${COLORS.bg})`,
          borderRadius: 40,
          border: `1.5px solid ${COLORS.border}`,
          padding: '60px 24px 30px',
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          position: 'relative',
          boxShadow: `0 0 60px ${COLORS.emerald500}10`,
        }}
      >
        <div
          style={{
            position: 'absolute',
            top: 16,
            left: '50%',
            transform: 'translateX(-50%)',
            width: 120,
            height: 8,
            borderRadius: 4,
            background: COLORS.zinc700,
          }}
        />

        <div
          style={{
            opacity: notificationOpacity,
            transform: `translateY(${notificationY}px)`,
            background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald700})`,
            borderRadius: 16,
            padding: '20px 24px',
            width: '100%',
            textAlign: 'center',
            marginBottom: 40,
          }}
        >
          <div style={{ fontSize: 14, fontWeight: 600, fontFamily: ARABIC_FONT, color: 'rgba(255,255,255,0.8)', direction: 'rtl' }}>
            رد جديد
          </div>
          <div style={{ fontSize: 20, fontWeight: 700, fontFamily: ARABIC_FONT, color: COLORS.white, marginTop: 4, direction: 'rtl' }}>
            شركة تدعوك للمقابلة!
          </div>
        </div>

        <div
          style={{
            opacity: statOpacity,
            transform: `scale(${statScale})`,
            width: '100%',
            background: COLORS.surface,
            borderRadius: 16,
            border: `1px solid ${COLORS.border}`,
            padding: '20px',
            display: 'flex',
            flexDirection: 'column',
            gap: 16,
          }}
        >
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              direction: 'rtl',
            }}
          >
            <span style={{ fontSize: 16, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc400 }}>ردود هذا الشهر</span>
            <span style={{ fontSize: 28, fontWeight: 800, fontFamily: ARABIC_FONT, color: COLORS.emerald400 }}>+300%</span>
          </div>
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              direction: 'rtl',
            }}
          >
            <span style={{ fontSize: 16, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc400 }}>دعوات للمقابلات</span>
            <span style={{ fontSize: 28, fontWeight: 800, fontFamily: ARABIC_FONT, color: COLORS.emerald400 }}>12</span>
          </div>
        </div>

        <div
          style={{
            position: 'absolute',
            bottom: 16,
            display: 'flex',
            gap: 6,
          }}
        >
          {[0, 1, 2].map((i) => (
            <div
              key={i}
              style={{
                width: 8,
                height: 8,
                borderRadius: '50%',
                background: i === 1 ? COLORS.emerald500 : COLORS.zinc700,
              }}
            />
          ))}
        </div>
      </div>

      <div
        style={{
          opacity: quoteOpacity,
          transform: `translateY(${quoteY}px)`,
          marginTop: 48,
          textAlign: 'center',
          maxWidth: 700,
          direction: 'rtl',
        }}
      >
        <div style={{ fontSize: 28, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc400, lineHeight: 1.6, fontStyle: 'italic' }}>
          &ldquo;تتمرن على الإنترفيو من البيت وتاخد تقييم فوري.&rdquo;
        </div>
        <div style={{ marginTop: 12, fontSize: 22, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc600 }}>
          سيراتي
        </div>
      </div>
    </AbsoluteFill>
  );
};
