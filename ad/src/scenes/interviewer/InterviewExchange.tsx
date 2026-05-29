import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring, Sequence } from 'remotion';
import { WaveformRing } from '../../components/InterviewUI';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const InterviewExchange: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const phase1Speaking = frame < 25;
  const phase2Listening = frame >= 25 && frame < 50;
  const phase3Speaking = frame >= 50;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg }}>
      <div style={{ display: 'flex', flexDirection: 'column', height: '100%' }}>
        <div
          style={{
            padding: `${24 * scale}px ${32 * scale}px`,
            display: 'flex',
            justifyContent: 'center',
          }}
        >
          <div style={{ display: 'flex', gap: 8 * scale }}>
            {['Speaking', 'Listening', 'Speaking'].map((label, i) => {
              const isActive = (i === 0 && phase1Speaking) || (i === 1 && phase2Listening) || (i === 2 && phase3Speaking);
              return (
                <div
                  key={i}
                  style={{
                    padding: `${6 * scale}px ${14 * scale}px`,
                    borderRadius: 8 * scale,
                    background: isActive ? `${COLORS.emerald500}15` : 'transparent',
                    border: `1px solid ${isActive ? COLORS.emerald500 + '30' : COLORS.border}`,
                    color: isActive ? COLORS.emerald400 : COLORS.zinc600,
                    fontSize: 11 * scale,
                    fontWeight: isActive ? 600 : 400,
                    fontFamily: FONT.primary,
                  }}
                >
                  {label}
                </div>
              );
            })}
          </div>
        </div>

        <div style={{ flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <WaveformRing
            frame={frame}
            isSpeaking={phase1Speaking || phase3Speaking}
            size={380}
            barCount={30}
            radius={160}
          />
        </div>

        <div
          style={{
            padding: `${32 * scale}px`,
            display: 'flex',
            justifyContent: 'center',
          }}
        >
          <div style={{ display: 'flex', gap: 12 * scale }}>
            {[
              { text: 'AI asks a question', active: phase1Speaking },
              { text: 'You respond naturally', active: phase2Listening },
              { text: 'AI follows up', active: phase3Speaking },
            ].map((item, i) => {
              const dotOpacity = item.active ? 1 : 0.2;
              return (
                <div
                  key={i}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: 8 * scale,
                    opacity: interpolate(
                      frame,
                      [i * 25, i * 25 + 10],
                      [0, dotOpacity],
                      { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }
                    ),
                  }}
                >
                  <div
                    style={{
                      width: 6 * scale,
                      height: 6 * scale,
                      borderRadius: '50%',
                      backgroundColor: item.active ? COLORS.emerald400 : COLORS.zinc600,
                    }}
                  />
                  <span style={{ color: item.active ? COLORS.zinc300 : COLORS.zinc600, fontSize: 12 * scale, fontFamily: FONT.primary }}>
                    {item.text}
                  </span>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
