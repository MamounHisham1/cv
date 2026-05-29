import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeatureMontage: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const features = [
    { icon: '🏗️', label: 'AI Builder', color: COLORS.emerald400 },
    { icon: '📄', label: '10 Templates', color: COLORS.blue400 },
    { icon: '🤖', label: 'ATS Score', color: COLORS.purple400 },
    { icon: '📊', label: 'AI Evaluator', color: COLORS.amber400 },
    { icon: '🎙️', label: 'AI Interviewer', color: COLORS.emerald400 },
  ];

  const activeIdx = Math.floor((frame / 12) % features.length);

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48 * scale }}>
        <div
          style={{
            width: 120 * scale,
            height: 120 * scale,
            borderRadius: 28 * scale,
            background: `${features[activeIdx].color}15`,
            border: `2px solid ${features[activeIdx].color}30`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            fontSize: 48 * scale,
            boxShadow: `0 0 40px ${features[activeIdx].color}15`,
            transition: 'none',
          }}
        >
          {features[activeIdx].icon}
        </div>

        <div
          style={{
            color: COLORS.white,
            fontSize: 28 * scale,
            fontWeight: 700,
            fontFamily: FONT.primary,
            letterSpacing: '-0.5px',
            transition: 'none',
          }}
        >
          {features[activeIdx].label}
        </div>

        <div style={{ display: 'flex', gap: 8 * scale }}>
          {features.map((_, i) => (
            <div
              key={i}
              style={{
                width: (i === activeIdx ? 24 : 8) * scale,
                height: 8 * scale,
                borderRadius: 4 * scale,
                backgroundColor: i === activeIdx ? features[i].color : COLORS.zinc700,
                transition: 'none',
              }}
            />
          ))}
        </div>
      </div>
    </AbsoluteFill>
  );
};
