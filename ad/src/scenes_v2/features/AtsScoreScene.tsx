import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT } from '../../styles';
import { GradientOrb } from '../../components/GradientOrb';

const RINGS = [
  { label: 'Keywords', max: 98 },
  { label: 'Format', max: 95 },
  { label: 'Impact', max: 88 },
];

export const AtsScoreScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const mainScore = Math.min(
    Math.floor(interpolate(frame, [40, 180], [45, 96], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' })),
    100
  );

  const gaugeAppear = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 16, stiffness: 80 } });
  const gaugeOpacity = interpolate(gaugeAppear, [0, 1], [0, 1]);
  const gaugeScale = interpolate(gaugeAppear, [0, 1], [0.8, 1]);

  const cx = 150;
  const cy = 150;
  const r = 125;
  const strokeWidth = 14;
  const circumference = 2 * Math.PI * r;
  const dashOffset = circumference * (1 - mainScore / 100);

  const labelFade = interpolate(frame, [5, 25], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

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
      <GradientOrb x={300} y={600} size={600} color={COLORS.emerald500} opacity={0.08} speed={0.015} offset={0} />
      <GradientOrb x={800} y={1200} size={450} color={COLORS.blue400} opacity={0.05} speed={0.02} offset={80} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 48, zIndex: 1 }}>
        <div style={{ opacity: labelFade }}>
          <span
            style={{
              fontSize: 24,
              fontWeight: 700,
              fontFamily: FONT.primary,
              color: COLORS.emerald400,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
            }}
          >
            ATS Score
          </span>
        </div>

        <div
          style={{
            opacity: gaugeOpacity,
            transform: `scale(${gaugeScale})`,
            position: 'relative',
            width: 300,
            height: 300,
          }}
        >
          <svg width={300} height={300} viewBox="0 0 300 300">
            <circle cx={cx} cy={cy} r={r} fill="none" stroke={COLORS.zinc800} strokeWidth={strokeWidth} />
            <circle
              cx={cx}
              cy={cy}
              r={r}
              fill="none"
              stroke={COLORS.emerald500}
              strokeWidth={strokeWidth}
              strokeLinecap="round"
              strokeDasharray={circumference}
              strokeDashoffset={dashOffset}
              transform={`rotate(-90 ${cx} ${cy})`}
              style={{
                filter: `drop-shadow(0 0 15px ${COLORS.emerald500}40)`,
              }}
            />
          </svg>

          <div
            style={{
              position: 'absolute',
              top: '50%',
              left: '50%',
              transform: 'translate(-50%, -50%)',
              textAlign: 'center',
            }}
          >
            <div
              style={{
                fontSize: 80,
                fontWeight: 900,
                fontFamily: FONT.primary,
                color: COLORS.white,
                lineHeight: 1,
                letterSpacing: -3,
                textShadow: `0 0 50px ${COLORS.emerald500}30`,
              }}
            >
              {mainScore}
            </div>
            <div style={{ fontSize: 20, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc500 }}>
              / 100
            </div>
          </div>
        </div>

        {frame >= 110 && (
          <div style={{ display: 'flex', gap: 48, marginTop: 16 }}>
            {RINGS.map((ring, i) => {
              const delay = 110 + i * 50;
              const itemEntrance = spring({
                frame: Math.max(0, frame - delay),
                fps,
                config: { damping: 14, stiffness: 100 },
              });
              const itemScore = Math.floor(
                interpolate(frame, [delay + 15, delay + 60], [0, ring.max], {
                  extrapolateLeft: 'clamp',
                  extrapolateRight: 'clamp',
                })
              );
              const itemOpacity = interpolate(itemEntrance, [0, 1], [0, 1]);

              return (
                <div
                  key={ring.label}
                  style={{
                    opacity: itemOpacity,
                    transform: `translateY(${interpolate(itemEntrance, [0, 1], [25, 0])}px)`,
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                    gap: 12,
                  }}
                >
                  <div
                    style={{
                      width: 110,
                      height: 110,
                      borderRadius: '50%',
                      background: `conic-gradient(${COLORS.emerald500} ${itemScore}%, ${COLORS.zinc800} ${itemScore}%)`,
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}
                  >
                    <div
                      style={{
                        width: 84,
                        height: 84,
                        borderRadius: '50%',
                        background: COLORS.bg,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                      }}
                    >
                      <span style={{ fontSize: 30, fontWeight: 800, fontFamily: FONT.primary, color: COLORS.white }}>
                        {itemScore}%
                      </span>
                    </div>
                  </div>
                  <span style={{ fontSize: 22, fontWeight: 500, fontFamily: FONT.primary, color: COLORS.zinc400 }}>
                    {ring.label}
                  </span>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </AbsoluteFill>
  );
};
