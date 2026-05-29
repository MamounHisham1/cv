import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const ScenarioScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Slide in "Tomorrow is..." from frame 10 to 30
  const line1Entrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 16, stiffness: 90 } });
  const line1Opacity = line1Entrance;
  const line1Y = interpolate(line1Entrance, [0, 1], [-30 * scale, 0]);

  // Slide in "your big job interview." from frame 25 to 45
  const line2Entrance = spring({ frame: Math.max(0, frame - 25), fps, config: { damping: 16, stiffness: 90 } });
  const line2Opacity = line2Entrance;
  const line2Y = interpolate(line2Entrance, [0, 1], [20 * scale, 0]);

  // Slide in "Are you actually ready?" from frame 60 to 80
  const line3Entrance = spring({ frame: Math.max(0, frame - 60), fps, config: { damping: 14, stiffness: 80 } });
  const line3Opacity = line3Entrance;
  const line3Y = interpolate(line3Entrance, [0, 1], [30 * scale, 0]);

  // Breathing heartbeat pulse on "ready?" starting at frame 80
  const pulse = frame >= 80 ? Math.sin((frame - 80) * 0.15) * 0.03 + 1 : 1;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden', padding: 80 * scale }}>
      <OrbSet visible={true} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 20 * scale, zIndex: 1 }}>
        <div
          style={{
            opacity: line1Opacity,
            transform: `translateY(${line1Y}px)`,
            color: COLORS.zinc400,
            fontSize: 32 * scale,
            fontWeight: 500,
            fontFamily: FONT.primary,
            letterSpacing: '-0.5px',
          }}
        >
          Tomorrow is...
        </div>
        <div
          style={{
            opacity: line2Opacity,
            transform: `translateY(${line2Y}px)`,
            color: COLORS.white,
            fontSize: 56 * scale,
            fontWeight: 800,
            fontFamily: FONT.primary,
            letterSpacing: '-2px',
            textAlign: 'center',
            lineHeight: 1.1,
          }}
        >
          your big <span style={{ color: COLORS.emerald400 }}>job interview</span>.
        </div>
        
        <div
          style={{
            opacity: line3Opacity,
            transform: `scale(${line3Entrance * pulse}) translateY(${line3Y}px)`,
            marginTop: 48 * scale,
            textAlign: 'center',
          }}
        >
          <div
            style={{
              color: COLORS.red400,
              fontSize: 40 * scale,
              fontWeight: 800,
              fontFamily: FONT.primary,
              letterSpacing: '-1.5px',
              textShadow: `0 0 40px ${COLORS.red500}30`,
            }}
          >
            Are you actually ready?
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
