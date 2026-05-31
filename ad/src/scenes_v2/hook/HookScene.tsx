import React from 'react';
import { AbsoluteFill, useCurrentFrame, interpolate, spring, useVideoConfig } from 'remotion';
import { COLORS, FONT } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

export const HookScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const counterFadeIn = interpolate(frame, [0, 8], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const counterScale = interpolate(frame, [0, 8], [1.3, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const showLabel = frame >= 40;
  const labelFade = interpolate(frame, [40, 55], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const showZero = frame >= 75;
  const zeroEntrance = spring({ frame: Math.max(0, frame - 75), fps, config: { damping: 20, stiffness: 120 } });
  const zeroOpacity = interpolate(zeroEntrance, [0, 1], [0, 1]);
  const zeroScale = interpolate(zeroEntrance, [0, 1], [1.5, 1]);
  const zeroColor = interpolate(frame, [75, 105], [0.2, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const callbacksFade = interpolate(frame, [90, 105], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const questionOpacity = interpolate(frame, [120, 145], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const questionY = interpolate(frame, [120, 145], [25, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const pulse = frame >= 120 ? Math.sin((frame - 120) * 0.1) * 0.02 + 1 : 1;

  const counterTick = Math.min(Math.floor(frame / 10) * 25, 100);

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
      <GradientOrb x={540} y={960} size={700} color={COLORS.emerald500} opacity={0.08} speed={0.01} offset={0} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 24, zIndex: 1 }}>
        {!showZero ? (
          <>
            <div
              style={{
                opacity: counterFadeIn,
                transform: `scale(${counterScale})`,
                fontSize: 200,
                fontWeight: 900,
                fontFamily: FONT.primary,
                color: COLORS.white,
                letterSpacing: -6,
                lineHeight: 1,
              }}
            >
              {counterTick}
            </div>
            {showLabel && (
              <div
                style={{
                  opacity: labelFade,
                  fontSize: 56,
                  fontWeight: 600,
                  fontFamily: FONT.primary,
                  color: COLORS.zinc400,
                  letterSpacing: -1,
                }}
              >
                CVs sent this month
              </div>
            )}
          </>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16 }}>
            <div
              style={{
                opacity: zeroOpacity,
                transform: `scale(${zeroScale * pulse})`,
                fontSize: 200,
                fontWeight: 900,
                fontFamily: FONT.primary,
                color: `rgba(239, 68, 68, ${zeroColor})`,
                letterSpacing: -6,
                lineHeight: 1,
                textShadow: zeroColor > 0.5 ? '0 0 80px rgba(239, 68, 68, 0.25)' : 'none',
              }}
            >
              0
            </div>
            <div
              style={{
                opacity: callbacksFade,
                fontSize: 56,
                fontWeight: 600,
                fontFamily: FONT.primary,
                color: COLORS.zinc300,
                letterSpacing: -1,
              }}
            >
              callbacks
            </div>
          </div>
        )}

        <div
          style={{
            opacity: questionOpacity,
            transform: `translateY(${questionY}px)`,
            marginTop: 80,
            fontSize: 40,
            fontWeight: 400,
            fontFamily: FONT.primary,
            color: COLORS.zinc500,
            fontStyle: 'italic',
            textAlign: 'center',
            maxWidth: 700,
            lineHeight: 1.3,
          }}
        >
          How many callbacks?
        </div>
      </div>
    </AbsoluteFill>
  );
};
