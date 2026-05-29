import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { FreeBadge } from '../../components/FeatureRecap';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FreeFirstTime: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const badgeScale = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 10, stiffness: 70 } });

  const sparkleOpacity = (delay: number) =>
    interpolate(frame, [delay, delay + 10, delay + 20], [0, 1, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 40 * scale }}>
        {[
          { x: -200, y: -150, delay: 10 },
          { x: 220, y: -100, delay: 15 },
          { x: -180, y: 130, delay: 20 },
          { x: 200, y: 150, delay: 25 },
          { x: 0, y: -200, delay: 18 },
        ].map((sparkle, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              left: `${LAYOUT.width / 2 + sparkle.x * scale}px`,
              top: `${LAYOUT.height / 2 + sparkle.y * scale}px`,
              opacity: sparkleOpacity(sparkle.delay),
              fontSize: 24 * scale,
            }}
          >
            ✦
          </div>
        ))}

        <FreeBadge frame={frame} />

        <div
          style={{
            opacity: interpolate(frame, [30, 45], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
            color: COLORS.zinc400,
            fontSize: 16 * scale,
            fontFamily: FONT.primary,
            textAlign: 'center',
            lineHeight: 1.6,
          }}
        >
          Practice your first AI mock interview
          <br />
          <span style={{ color: COLORS.emerald400, fontWeight: 600 }}>at absolutely no cost</span>
        </div>
      </div>
    </AbsoluteFill>
  );
};
