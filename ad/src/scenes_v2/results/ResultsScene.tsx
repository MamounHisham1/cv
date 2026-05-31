import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

export const ResultsScene: React.FC = () => {
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
          borderRadius: 48,
          border: `2px solid ${COLORS.zinc700}`,
          background: COLORS.bgLight,
          padding: '36px 24px 24px',
          position: 'relative',
          overflow: 'hidden',
          zIndex: 1,
        }}
      >
        <div
          style={{
            position: 'absolute',
            top: 14,
            left: '50%',
            transform: 'translateX(-50%)',
            width: 100,
            height: 8,
            borderRadius: 4,
            background: COLORS.zinc700,
          }}
        />

        <div style={{ fontSize: 16, fontWeight: 600, fontFamily: FONT.primary, color: COLORS.zinc400, textAlign: 'center', marginBottom: 24 }}>
          9:41
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: 12, marginBottom: 16 }}>
          <div style={{ padding: '12px 16px', background: COLORS.zinc800, borderRadius: 16, opacity: 0.6 }}>
            <div style={{ width: '60%', height: 10, borderRadius: 4, background: COLORS.zinc700, marginBottom: 8 }} />
            <div style={{ width: '40%', height: 8, borderRadius: 3, background: COLORS.zinc700 }} />
          </div>
          <div style={{ padding: '12px 16px', background: COLORS.zinc800, borderRadius: 16, opacity: 0.4 }}>
            <div style={{ width: '50%', height: 10, borderRadius: 4, background: COLORS.zinc700, marginBottom: 8 }} />
            <div style={{ width: '35%', height: 8, borderRadius: 3, background: COLORS.zinc700 }} />
          </div>
        </div>

        <div
          style={{
            opacity: notificationOpacity,
            transform: `translateY(${notificationY}px)`,
            background: 'linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(16, 185, 129, 0.05))',
            border: '1px solid rgba(16, 185, 129, 0.25)',
            borderRadius: 16,
            padding: '18px 20px',
          }}
        >
          <div style={{ display: 'flex', alignItems: 'flex-start', gap: 14 }}>
            <div
              style={{
                width: 44,
                height: 44,
                borderRadius: 12,
                background: COLORS.emerald500,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: 22,
                flexShrink: 0,
              }}
            >
              @
            </div>
            <div>
              <div style={{ fontSize: 16, fontWeight: 700, fontFamily: FONT.primary, color: COLORS.white }}>
                Interview Invitation
              </div>
              <div style={{ fontSize: 14, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc400, marginTop: 4 }}>
                Google &bull; Product Design Lead
              </div>
              <div style={{ fontSize: 13, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.emerald400, marginTop: 6 }}>
                Scheduled for next Tuesday
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        style={{
          opacity: statOpacity,
          transform: `scale(${statScale})`,
          marginTop: 48,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          zIndex: 1,
        }}
      >
        <div
          style={{
            fontSize: 120,
            fontWeight: 900,
            fontFamily: FONT.primary,
            color: COLORS.emerald400,
            lineHeight: 1,
            letterSpacing: -4,
            textShadow: `0 0 60px ${COLORS.emerald500}25`,
          }}
        >
          3x
        </div>
        <div style={{ fontSize: 40, fontWeight: 600, fontFamily: FONT.primary, color: COLORS.white, letterSpacing: -1, marginTop: 8 }}>
          more callbacks
        </div>
        <div style={{ fontSize: 28, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc500 }}>
          in just 2 weeks
        </div>
      </div>

      <div
        style={{
          opacity: quoteOpacity,
          transform: `translateY(${quoteY}px)`,
          marginTop: 40,
          textAlign: 'center',
          maxWidth: 700,
          zIndex: 1,
        }}
      >
        <div style={{ fontSize: 26, fontWeight: 400, fontFamily: FONT.primary, color: COLORS.zinc400, fontStyle: 'italic', lineHeight: 1.5 }}>
          &ldquo;I got 3 interviews in the first week. SeratyAI changed everything.&rdquo;
        </div>
        <div style={{ fontSize: 20, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc600, marginTop: 10 }}>
          &mdash; Ahmed, Product Designer
        </div>
      </div>
    </AbsoluteFill>
  );
};
