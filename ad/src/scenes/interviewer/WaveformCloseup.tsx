import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { WaveformRing } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const WaveformCloseup: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  const isSpeaking = true;
  const zoomProgress = interpolate(frame, [0, 60], [1, 1.25], { extrapolateRight: 'clamp' });

  const bars = 36;
  const centerSize = 500;
  const radius = 220;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
      <div
        style={{
          opacity: interpolate(frame, [0, 10], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
          transform: `scale(${zoomProgress})`,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: 60 * scale,
        }}
      >
        <WaveformRing frame={frame} isSpeaking={isSpeaking} size={centerSize} barCount={bars} radius={radius} />

        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 12 * scale }}>
          <div style={{ color: COLORS.emerald400, fontSize: 28 * scale, fontWeight: 700, fontFamily: FONT.primary }}>
            AI is speaking
          </div>
          <div style={{ color: COLORS.zinc500, fontSize: 16 * scale, fontFamily: FONT.primary }}>
            Asking questions tailored to your CV
          </div>
        </div>
      </div>

      <div
        style={{
          position: 'absolute',
          top: 100 * scale,
          left: '50%',
          transform: 'translateX(-50%)',
          background: `${COLORS.emerald500}10`,
          border: `1px solid ${COLORS.emerald500}25`,
          borderRadius: 12 * scale,
          padding: `${10 * scale}px ${20 * scale}px`,
          color: COLORS.emerald400,
          fontSize: 14 * scale,
          fontFamily: FONT.primary,
          fontWeight: 500,
          opacity: interpolate(frame, [5, 15], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
        }}
      >
        🎙 Question 3 of 8
      </div>
    </AbsoluteFill>
  );
};
