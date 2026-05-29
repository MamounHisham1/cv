import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const Connecting: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  const pulse = Math.sin(frame * 0.2) * 0.5 + 0.5;
  const ringScale = interpolate(pulse, [0, 1], [0.8, 1.2]);
  const ringOpacity = interpolate(pulse, [0, 1], [0.4, 0]);

  const dotCount = 3;
  const activeDot = Math.floor((frame / 8) % (dotCount + 2));

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
      <OrbSet visible={true} />
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 36 * scale, zIndex: 1 }}>
        <div style={{ position: 'relative', width: 120 * scale, height: 120 * scale }}>
          <div
            style={{
              position: 'absolute',
              inset: 0,
              borderRadius: '50%',
              border: `2px solid ${COLORS.emerald500}40`,
              transform: `scale(${ringScale})`,
              opacity: ringOpacity,
            }}
          />
          <div
            style={{
              position: 'absolute',
              inset: 10 * scale,
              borderRadius: '50%',
              border: `2px solid ${COLORS.emerald500}30`,
              transform: `scale(${interpolate(pulse, [0, 1], [0.9, 1.1])})`,
              opacity: interpolate(pulse, [0, 1], [0.3, 0]),
            }}
          />
          <div
            style={{
              position: 'absolute',
              inset: 0,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <svg width={44 * scale} height={44 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={1.5} opacity={0.6 + pulse * 0.4}>
              <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.858 15.355-5.858 21.213 0" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 12 * scale }}>
          <div style={{ color: COLORS.zinc300, fontSize: 20 * scale, fontWeight: 600, fontFamily: FONT.primary }}>
            Connecting
            <span style={{ opacity: activeDot >= 1 ? 1 : 0.2 }}>.</span>
            <span style={{ opacity: activeDot >= 2 ? 1 : 0.2 }}>.</span>
            <span style={{ opacity: activeDot >= 3 ? 1 : 0.2 }}>.</span>
          </div>
          <div style={{ color: COLORS.zinc600, fontSize: 14 * scale, fontFamily: FONT.primary }}>
            Establishing voice connection
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
