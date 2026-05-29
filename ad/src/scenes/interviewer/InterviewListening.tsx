import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { WaveformRing } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const InterviewListening: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  const isListening = true;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div
        style={{
          opacity: interpolate(frame, [0, 10], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }),
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: 48 * scale,
        }}
      >
        <WaveformRing frame={frame} isSpeaking={false} size={320} barCount={24} radius={134} />

        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 12 * scale }}>
          <div style={{ color: COLORS.emerald400, fontSize: 22 * scale, fontWeight: 600, fontFamily: FONT.primary }}>
            Listening
          </div>
          <div style={{ color: COLORS.zinc500, fontSize: 14 * scale, fontFamily: FONT.primary, textAlign: 'center', maxWidth: 500 * scale, lineHeight: 1.6 }}>
            Speak naturally — the AI listens and adapts
          </div>
        </div>

        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: 10 * scale,
            background: `${COLORS.emerald500}08`,
            borderRadius: 20 * scale,
            padding: `${8 * scale}px ${20 * scale}px`,
            border: `1px solid ${COLORS.emerald500}15`,
          }}
        >
          <div
            style={{
              width: 8 * scale,
              height: 8 * scale,
              borderRadius: '50%',
              backgroundColor: COLORS.emerald500,
              boxShadow: `0 0 ${8 + Math.sin(frame * 0.3) * 4}px ${COLORS.emerald500}60`,
            }}
          />
          <span style={{ color: COLORS.emerald400, fontSize: 12 * scale, fontFamily: FONT.primary }}>Mic active</span>
        </div>
      </div>
    </AbsoluteFill>
  );
};
