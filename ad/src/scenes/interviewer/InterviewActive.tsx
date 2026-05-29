import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate } from 'remotion';
import { WaveformRing, LiveIndicator } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const InterviewActive: React.FC = () => {
  const frame = useCurrentFrame();
  const scale = LAYOUT.width / 1080;

  const isSpeaking = frame % 90 < 50;

  const elapsed = Math.floor(frame / 30);
  const minutes = Math.floor(elapsed / 60);
  const seconds = elapsed % 60;

  const fadeIn = interpolate(frame, [0, 15], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg }}>
      <div style={{ opacity: fadeIn, display: 'flex', flexDirection: 'column', height: '100%' }}>
        <div
          style={{
            padding: `${20 * scale}px ${28 * scale}px`,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            borderBottom: `1px solid ${COLORS.border5}`,
          }}
        >
          <LiveIndicator frame={frame} />
          <div style={{ display: 'flex', alignItems: 'center', gap: 16 * scale }}>
            <span style={{ color: COLORS.zinc400, fontSize: 14 * scale, fontFamily: FONT.mono }}>
              {minutes}:{seconds.toString().padStart(2, '0')}
            </span>
            <span style={{ color: COLORS.zinc500, fontSize: 12 * scale }}>|</span>
            <span style={{ color: COLORS.zinc500, fontSize: 12 * scale, fontFamily: FONT.primary }}>
              {Math.min(Math.floor(frame / 60), 5)} answered
            </span>
          </div>
        </div>

        <div
          style={{
            flex: 1,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            gap: 48 * scale,
          }}
        >
          <WaveformRing frame={frame} isSpeaking={isSpeaking} size={360} barCount={28} radius={150} />

          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 8 * scale }}>
            <span
              style={{
                color: isSpeaking ? COLORS.emerald400 : COLORS.zinc400,
                fontSize: 18 * scale,
                fontWeight: 600,
                fontFamily: FONT.primary,
                transition: 'none',
              }}
            >
              {isSpeaking ? 'Speaking...' : 'Listening...'}
            </span>
            <span style={{ color: COLORS.zinc600, fontSize: 13 * scale, fontFamily: FONT.primary }}>
              {isSpeaking ? 'AI is responding...' : 'Speak naturally — the AI is listening'}
            </span>
          </div>
        </div>

        <div
          style={{
            padding: `${20 * scale}px`,
            display: 'flex',
            justifyContent: 'center',
          }}
        >
          <span style={{ display: 'flex', alignItems: 'center', gap: 8 * scale, color: COLORS.zinc600, fontSize: 12 * scale, fontFamily: FONT.primary }}>
            <span style={{ width: 8 * scale, height: 8 * scale, borderRadius: '50%', backgroundColor: COLORS.emerald500 }} />
            Mic active
          </span>
        </div>
      </div>
    </AbsoluteFill>
  );
};
