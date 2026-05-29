import React from 'react';
import { useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../styles';

export const FeatureRecapCard: React.FC<{
  frame: number;
  icon: React.ReactNode;
  title: string;
  description: string;
  accentColor?: string;
  delay?: number;
}> = ({ frame, icon, title, description, accentColor = COLORS.emerald400, delay = 0 }) => {
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const entrance = spring({
    frame: Math.max(0, frame - delay),
    fps,
    config: { damping: 16, stiffness: 100 },
  });

  const slideY = interpolate(entrance, [0, 1], [40, 0]);

  return (
    <div
      style={{
        opacity: entrance,
        transform: `translateY(${slideY}px)`,
        background: COLORS.bgCard,
        backdropFilter: 'blur(24px)',
        borderRadius: 20 * scale,
        border: `1px solid ${COLORS.border5}`,
        padding: `${28 * scale}px ${32 * scale}px`,
        display: 'flex',
        alignItems: 'center',
        gap: 24 * scale,
        width: LAYOUT.width - 120 * scale,
      }}
    >
      <div
        style={{
          width: 64 * scale,
          height: 64 * scale,
          borderRadius: 16 * scale,
          background: `${accentColor}15`,
          border: `1px solid ${accentColor}25`,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          fontSize: 28 * scale,
          flexShrink: 0,
        }}
      >
        {icon}
      </div>
      <div>
        <div style={{ color: COLORS.white, fontSize: 18 * scale, fontWeight: 600, fontFamily: FONT.primary, marginBottom: 4 * scale }}>
          {title}
        </div>
        <div style={{ color: COLORS.zinc400, fontSize: 14 * scale, fontFamily: FONT.primary, lineHeight: 1.5 }}>
          {description}
        </div>
      </div>
    </div>
  );
};

export const FreeBadge: React.FC<{
  frame: number;
}> = ({ frame }) => {
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const badgeScale = spring({
    frame,
    fps,
    config: { damping: 10, stiffness: 80 },
  });

  const bounceScale = interpolate(
    Math.sin(frame * 0.15) * 0.5 + 0.5,
    [0, 1],
    [0.98, 1.02]
  );

  const finalScale = badgeScale * bounceScale;

  return (
    <div
      style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 24 * scale,
        transform: `scale(${finalScale})`,
      }}
    >
      <div
        style={{
          background: `linear-gradient(135deg, ${COLORS.emerald500}, ${COLORS.emerald700})`,
          borderRadius: 28 * scale,
          padding: `${32 * scale}px ${56 * scale}px`,
          boxShadow: `0 0 60px ${COLORS.emerald500}30, 0 0 120px ${COLORS.emerald500}15`,
          textAlign: 'center',
        }}
      >
        <div style={{ color: COLORS.white, fontSize: 42 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-1px' }}>
          FREE
        </div>
        <div style={{ color: 'rgba(255,255,255,0.85)', fontSize: 18 * scale, fontWeight: 500, fontFamily: FONT.primary, marginTop: 8 * scale }}>
          for your first interview!
        </div>
      </div>
      <div
        style={{
          color: COLORS.zinc400,
          fontSize: 16 * scale,
          fontFamily: FONT.primary,
          textAlign: 'center',
          maxWidth: 600 * scale,
          lineHeight: 1.6,
        }}
      >
        Try the AI Interviewer at no cost.
        <br />
        <span style={{ color: COLORS.emerald400 }}>No credit card required.</span>
      </div>
    </div>
  );
};

export const CreditCard: React.FC<{
  frame: number;
}> = ({ frame }) => {
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const entrance = spring({
    frame,
    fps,
    config: { damping: 16, stiffness: 90 },
  });

  return (
    <div
      style={{
        opacity: entrance,
        transform: `scale(${interpolate(entrance, [0, 1], [0.8, 1])})`,
        background: COLORS.bgCard,
        backdropFilter: 'blur(24px)',
        borderRadius: 24 * scale,
        border: `1px solid ${COLORS.border5}`,
        padding: `${40 * scale}px ${48 * scale}px`,
        textAlign: 'center',
        width: LAYOUT.width - 120 * scale,
      }}
    >
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 12 * scale, marginBottom: 20 * scale }}>
        <div
          style={{
            width: 48 * scale,
            height: 48 * scale,
            borderRadius: '50%',
            background: `linear-gradient(135deg, ${COLORS.amber400}, ${COLORS.amber500})`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: `0 0 20px ${COLORS.amber400}30`,
          }}
        >
          <svg width={22 * scale} height={22 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.bg} strokeWidth={2.5}>
            <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="currentColor" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
      </div>
      <div style={{ color: COLORS.white, fontSize: 36 * scale, fontWeight: 800, fontFamily: FONT.primary }}>
        30 Free Credits
      </div>
      <div style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, marginTop: 12 * scale, lineHeight: 1.6 }}>
        Every month, on the house.
      </div>
    </div>
  );
};
