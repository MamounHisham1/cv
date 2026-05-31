import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

export const AtsRejectionScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const cvEntrance = spring({ frame: Math.max(0, frame - 10), fps, config: { damping: 18, stiffness: 100 } });
  const cvOpacity = interpolate(cvEntrance, [0, 1], [0, 1]);
  const cvY = interpolate(cvEntrance, [0, 1], [80, 0]);

  const scanProgress = interpolate(frame, [50, 110], [0, 100], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const scanOpacity = interpolate(frame, [40, 55], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const scoreAppear = frame >= 120;
  const scoreEntrance = spring({ frame: Math.max(0, frame - 120), fps, config: { damping: 16, stiffness: 90 } });
  const scoreOpacity = interpolate(scoreEntrance, [0, 1], [0, 1]);

  const rejectAppear = frame >= 190;
  const rejectEntrance = spring({ frame: Math.max(0, frame - 190), fps, config: { damping: 12, stiffness: 200 } });
  const rejectScale = interpolate(rejectEntrance, [0, 1], [2, 1]);
  const rejectOpacity = interpolate(rejectEntrance, [0, 1], [0, 1]);

  const subtextFade = interpolate(frame, [220, 260], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const subtextY = interpolate(frame, [220, 260], [25, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const bgPulse = frame >= 190 ? Math.sin((frame - 190) * 0.08) * 3 : 0;

  return (
    <AbsoluteFill
      style={{
        backgroundColor: COLORS.bg,
        justifyContent: 'center',
        alignItems: 'center',
        overflow: 'hidden',
        padding: 60,
      }}
    >
      <GradientOrb x={300} y={500} size={600} color="#ef4444" opacity={0.06} speed={0.01} offset={0} />
      <GradientOrb x={800} y={1400} size={450} color="#ef4444" opacity={0.04} speed={0.015} offset={50} />

      <div
        style={{
          opacity: cvOpacity,
          transform: `translateY(${cvY}px)`,
          width: '90%',
          maxWidth: 900,
          background: `linear-gradient(135deg, ${COLORS.bgLight}, ${COLORS.zinc900})`,
          borderRadius: 24,
          border: `1px solid ${COLORS.border}`,
          padding: '50px 60px',
          position: 'relative',
          overflow: 'hidden',
        }}
      >
        <div
          style={{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: 10,
            background: `linear-gradient(90deg, ${COLORS.zinc700}, ${COLORS.zinc600})`,
          }}
        />

        <div style={{ display: 'flex', alignItems: 'center', gap: 24, marginBottom: 36 }}>
          <div style={{ width: 72, height: 72, borderRadius: '50%', background: COLORS.zinc700 }} />
          <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
            <div style={{ width: 240, height: 18, borderRadius: 4, background: COLORS.zinc700 }} />
            <div style={{ width: 170, height: 14, borderRadius: 4, background: COLORS.zinc700 }} />
          </div>
        </div>

        {[0, 1, 2, 3, 4].map((i) => (
          <div key={i} style={{ display: 'flex', gap: 16, marginBottom: 20, alignItems: 'center' }}>
            <div style={{ width: 10, height: 10, borderRadius: '50%', background: COLORS.zinc700, flexShrink: 0 }} />
            <div style={{ width: `${50 + i * 8}%`, height: 14, borderRadius: 4, background: COLORS.zinc700 }} />
          </div>
        ))}

        {scanOpacity > 0 && (
          <div
            style={{
              position: 'absolute',
              left: 0,
              top: `${scanProgress}%`,
              width: '100%',
              height: 4,
              background: 'linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.6), transparent)',
              opacity: scanOpacity,
              boxShadow: '0 0 25px rgba(239, 68, 68, 0.3)',
            }}
          />
        )}

        {scoreAppear && (
          <div
            style={{
              position: 'absolute',
              top: 24,
              right: 24,
              opacity: scoreOpacity,
              background: 'rgba(239, 68, 68, 0.15)',
              border: '1px solid rgba(239, 68, 68, 0.3)',
              borderRadius: 16,
              padding: '16px 28px',
              textAlign: 'center',
            }}
          >
            <div style={{ fontSize: 18, fontWeight: 700, fontFamily: FONT.primary, color: '#fca5a5', letterSpacing: '0.06em' }}>
              ATS MATCH
            </div>
            <div style={{ fontSize: 56, fontWeight: 900, fontFamily: FONT.primary, color: '#ef4444', lineHeight: 1.1 }}>
              45%
            </div>
            <div style={{ fontSize: 16, fontWeight: 700, fontFamily: FONT.primary, color: '#fca5a5', letterSpacing: '0.05em' }}>
              BELOW THRESHOLD
            </div>
          </div>
        )}
      </div>

      {rejectAppear && (
        <div
          style={{
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: `translate(-50%, -50%) scale(${rejectScale})`,
            opacity: rejectOpacity,
            background: 'rgba(239, 68, 68, 0.12)',
            border: '4px solid rgba(239, 68, 68, 0.4)',
            borderRadius: 20,
            padding: '20px 56px',
            rotate: '-12deg',
          }}
        >
          <span
            style={{
              fontSize: 72,
              fontWeight: 900,
              fontFamily: FONT.primary,
              color: '#ef4444',
              letterSpacing: 10,
              textShadow: '0 0 50px rgba(239, 68, 68, 0.3)',
            }}
          >
            REJECTED
          </span>
        </div>
      )}

      {rejectAppear && (
        <div
          style={{
            opacity: subtextFade,
            transform: `translateY(${subtextY}px)`,
            marginTop: 48,
            fontSize: 36,
            fontWeight: 500,
            fontFamily: FONT.primary,
            color: COLORS.zinc400,
            textAlign: 'center',
            maxWidth: 700,
            lineHeight: 1.3,
          }}
        >
          Before a human even sees it, an AI rejects you.
        </div>
      )}

      {rejectAppear && (
        <div
          style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            background: `radial-gradient(circle at 50% 50%, rgba(239, 68, 68, ${0.03 + bgPulse * 0.005}), transparent 70%)`,
            pointerEvents: 'none',
          }}
        />
      )}
    </AbsoluteFill>
  );
};
