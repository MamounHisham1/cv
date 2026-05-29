import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const HookReveal: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  const titleScale = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 12, stiffness: 80 } });
  const titleEntrance = interpolate(titleScale, [0, 1], [1.3, 1]);

  const subtitleOpacity = interpolate(frame, [20, 35], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const micScale = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 14, stiffness: 100 } });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center' }}>
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 36 * scale, padding: 60 * scale }}>
        <div
          style={{
            width: 140 * scale,
            height: 140 * scale,
            borderRadius: '50%',
            background: `linear-gradient(135deg, ${COLORS.emerald500}20, ${COLORS.emerald600}10)`,
            border: `2px solid ${COLORS.emerald500}30`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            transform: `scale(${micScale})`,
            boxShadow: `0 0 80px ${COLORS.emerald500}15`,
          }}
        >
          <svg width={60 * scale} height={60 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={1.5}>
            <path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>

        <div
          style={{
            transform: `scale(${titleEntrance})`,
            textAlign: 'center',
          }}
        >
          <div style={{ color: COLORS.white, fontSize: 56 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-2px', lineHeight: 1.1 }}>
            AI <span style={{ color: COLORS.emerald400 }}>Interviewer</span>
          </div>
        </div>

        <div
          style={{
            opacity: subtitleOpacity,
            color: COLORS.zinc400,
            fontSize: 22 * scale,
            fontFamily: FONT.primary,
            textAlign: 'center',
            maxWidth: 700 * scale,
            lineHeight: 1.5,
          }}
        >
          Practice with a voice-based AI that adapts to your CV.
          <br />
          Get scored. Get better. Get hired.
        </div>
      </div>
    </AbsoluteFill>
  );
};
