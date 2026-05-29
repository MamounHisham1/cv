import React from 'react';
import { useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../styles';

export const WaveformRing: React.FC<{
  frame: number;
  isSpeaking: boolean;
  barCount?: number;
  radius?: number;
  size?: number;
}> = ({ frame, isSpeaking, barCount = 24, radius = 104, size = 280 }) => {
  const bars = [];
  const centerOffset = size / 2;

  for (let i = 0; i < barCount; i++) {
    const angle = (i * 360) / barCount;
    const rad = (angle * Math.PI) / 180;
    const phaseOffset = i * 0.07;
    const timeVal = frame / 30;

    let barHeight;
    if (isSpeaking) {
      barHeight = 8 + 20 * Math.abs(Math.sin(timeVal * 4 + phaseOffset * 10));
    } else {
      barHeight = 4 + 6 * Math.abs(Math.sin(timeVal * 1.5 + phaseOffset * 5));
    }

    const barWidth = 6;
    const x = centerOffset + Math.cos(rad) * radius - barWidth / 2;
    const y = centerOffset + Math.sin(rad) * radius;

    bars.push(
      <div
        key={i}
        style={{
          position: 'absolute',
          left: x,
          top: y - barHeight / 2,
          width: barWidth,
          height: barHeight,
          borderRadius: 3,
          backgroundColor: isSpeaking ? COLORS.emerald400 : COLORS.zinc600,
          transform: `rotate(${angle}deg)`,
          transformOrigin: 'center center',
          boxShadow: isSpeaking ? `0 0 8px ${COLORS.emerald500}40` : 'none',
        }}
      />
    );
  }

  return (
    <div style={{ position: 'relative', width: size, height: size }}>
      <div
        style={{
          position: 'absolute',
          inset: 0,
          borderRadius: '50%',
          background: isSpeaking
            ? `radial-gradient(circle, ${COLORS.emerald500}15, transparent 70%)`
            : `radial-gradient(circle, ${COLORS.zinc800}30, transparent 70%)`,
        }}
      />
      {bars}
      <div
        style={{
          position: 'absolute',
          left: '50%',
          top: '50%',
          transform: 'translate(-50%, -50%)',
          width: size * 0.45,
          height: size * 0.45,
          borderRadius: '50%',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          background: isSpeaking
            ? `${COLORS.emerald500}20`
            : `${COLORS.zinc800}`,
          border: `2px solid ${isSpeaking ? COLORS.emerald500 + '40' : COLORS.zinc700}`,
          boxShadow: isSpeaking
            ? `0 0 60px ${COLORS.emerald500}20`
            : 'none',
        }}
      >
        {isSpeaking ? (
          <svg width={size * 0.2} height={size * 0.2} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={1.5}>
            <path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414M7.05 4.05A9 9 0 003 12c0 2.485 1.005 4.735 2.636 6.364" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        ) : (
          <svg width={size * 0.2} height={size * 0.2} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={1.5}>
            <path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        )}
      </div>
    </div>
  );
};

export const InterviewSetupCard: React.FC<{
  frame: number;
  selectedType?: string;
  highlightSection?: 'cv' | 'type' | 'button' | 'all';
  focusSection?: 'cv' | 'type' | 'button' | 'none';
  dropdownOpen?: boolean;
  dropdownSelectedIndex?: number;
  triggerClickRipple?: boolean;
}> = ({
  frame,
  selectedType = 'mixed',
  highlightSection = 'all',
  focusSection = 'none',
  dropdownOpen = false,
  dropdownSelectedIndex = 0,
  triggerClickRipple = false,
}) => {
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;
  const padding = 48 * scale;

  const cvs = [
    'Senior Software Engineer',
    'Full Stack Developer',
    'Frontend Engineer',
    'DevOps Engineer',
  ];

  // Camera focal zooms using spring physics
  const zoomSpring = spring({ frame, fps, config: { damping: 18, stiffness: 80 } });
  
  let transformStyle = '';
  let cvOpacity = 1;
  let typeOpacity = 1;
  let buttonOpacity = 1;

  if (focusSection === 'cv') {
    const scaleFactor = interpolate(zoomSpring, [0, 1], [1, 1.08]);
    const translateY = interpolate(zoomSpring, [0, 1], [0, 160 * scale]);
    transformStyle = `scale(${scaleFactor}) translateY(${translateY}px)`;
    typeOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
    buttonOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
  } else if (focusSection === 'type') {
    const scaleFactor = interpolate(zoomSpring, [0, 1], [1, 1.08]);
    const translateY = interpolate(zoomSpring, [0, 1], [0, -10 * scale]);
    transformStyle = `scale(${scaleFactor}) translateY(${translateY}px)`;
    cvOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
    buttonOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
  } else if (focusSection === 'button') {
    const scaleFactor = interpolate(zoomSpring, [0, 1], [1, 1.08]);
    const translateY = interpolate(zoomSpring, [0, 1], [0, -220 * scale]);
    transformStyle = `scale(${scaleFactor}) translateY(${translateY}px)`;
    cvOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
    typeOpacity = interpolate(zoomSpring, [0, 1], [1, 0.12]);
  } else {
    const slideUp = spring({ frame, fps, config: { damping: 18, stiffness: 100 } });
    const translateY = interpolate(slideUp, [0, 1], [60 * scale, 0]);
    transformStyle = `translateY(${translateY}px)`;
  }

  return (
    <div
      style={{
        transform: transformStyle,
        opacity: focusSection !== 'none' ? 1 : interpolate(spring({ frame, fps, config: { damping: 18, stiffness: 100 } }), [0, 1], [0, 1]),
        background: COLORS.bgCard,
        backdropFilter: 'blur(24px)',
        borderRadius: 24 * scale,
        border: `1px solid ${COLORS.border5}`,
        padding: padding,
        width: LAYOUT.width - 80 * scale,
        fontFamily: FONT.primary,
        transition: 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
      }}
    >
      <div style={{ opacity: focusSection !== 'none' ? 0.12 : 1, transition: 'opacity 0.2s ease' }}>
        <h2 style={{ color: COLORS.white, fontSize: 28 * scale, fontWeight: 700, marginBottom: 20 * scale, letterSpacing: '-0.5px' }}>
          AI Interviewer
        </h2>
        <p style={{ color: COLORS.zinc400, fontSize: 16 * scale, marginBottom: 32 * scale, lineHeight: 1.6 }}>
          Select a CV and optionally paste a job description to start a realistic, voice-based mock interview.
        </p>
      </div>

      {(highlightSection === 'cv' || highlightSection === 'all') && (
        <div style={{ marginBottom: 24 * scale, opacity: cvOpacity, transition: 'opacity 0.2s ease', position: 'relative' }}>
          <label style={{ display: 'block', color: COLORS.zinc300, fontSize: 14 * scale, fontWeight: 500, marginBottom: 8 * scale }}>
            Select CV
          </label>
          <div
            style={{
              background: COLORS.bg,
              border: `1px solid ${focusSection === 'cv' ? COLORS.emerald500 : COLORS.border}`,
              borderRadius: 12 * scale,
              padding: `${12 * scale}px ${16 * scale}px`,
              color: COLORS.white,
              fontSize: 16 * scale,
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              boxShadow: focusSection === 'cv' ? `0 0 15px ${COLORS.emerald500}20` : 'none',
            }}
          >
            <span>{cvs[dropdownSelectedIndex]}</span>
            <span style={{ color: focusSection === 'cv' ? COLORS.emerald400 : COLORS.zinc500 }}>▾</span>
          </div>

          {dropdownOpen && (
            <div
              style={{
                position: 'absolute',
                top: '100%',
                left: 0,
                right: 0,
                background: COLORS.bgLight,
                border: `1px solid ${COLORS.border}`,
                borderRadius: 12 * scale,
                overflow: 'hidden',
                zIndex: 10,
                marginTop: 4 * scale,
                boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
              }}
            >
              {cvs.map((cv, i) => (
                <div
                  key={cv}
                  style={{
                    padding: `${12 * scale}px ${18 * scale}px`,
                    color: i === dropdownSelectedIndex ? COLORS.emerald400 : COLORS.zinc400,
                    fontSize: 14 * scale,
                    fontFamily: FONT.primary,
                    background: i === dropdownSelectedIndex ? `${COLORS.emerald500}10` : 'transparent',
                    borderLeft: i === dropdownSelectedIndex ? `3px solid ${COLORS.emerald500}` : `3px solid transparent`,
                  }}
                >
                  {cv}
                  <span style={{ color: COLORS.zinc600, fontSize: 12 * scale, marginLeft: 8 * scale }}>— Updated recently</span>
                </div>
              ))}
            </div>
          )}
        </div>
      )}

      {(highlightSection === 'type' || highlightSection === 'all') && (
        <div style={{ marginBottom: 24 * scale, opacity: typeOpacity, transition: 'opacity 0.2s ease' }}>
          <label style={{ display: 'block', color: COLORS.zinc300, fontSize: 14 * scale, fontWeight: 500, marginBottom: 12 * scale }}>
            Interview Focus
          </label>
          <div style={{ display: 'flex', gap: 12 * scale }}>
            {[
              {
                label: 'Behavioral',
                desc: 'STAR method',
                icon: (
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                ),
                key: 'behavioral',
              },
              {
                label: 'Technical',
                desc: 'Hard skills',
                icon: (
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 005 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.51-1z" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                ),
                key: 'technical',
              },
              {
                label: 'Mixed',
                desc: 'Comprehensive',
                icon: (
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                  </svg>
                ),
                key: 'mixed',
              },
            ].map((type) => {
              const isSelected = selectedType === type.key;
              return (
                <div
                  key={type.key}
                  style={{
                    flex: 1,
                    background: COLORS.bg,
                    border: `1px solid ${isSelected ? COLORS.emerald500 : COLORS.border}`,
                    borderRadius: 12 * scale,
                    padding: `${14 * scale}px ${12 * scale}px`,
                    boxShadow: isSelected ? `0 0 12px ${COLORS.emerald500}20` : 'none',
                    display: 'flex',
                    flexDirection: 'column',
                  }}
                >
                  <div
                    style={{
                      width: 24 * scale,
                      height: 24 * scale,
                      marginBottom: 8 * scale,
                      color: isSelected ? COLORS.emerald400 : COLORS.zinc500,
                    }}
                  >
                    {type.icon}
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span style={{ color: COLORS.white, fontSize: 13 * scale, fontWeight: 500 }}>{type.label}</span>
                    {isSelected && (
                      <svg width={14 * scale} height={14 * scale} viewBox="0 0 20 20" fill={COLORS.emerald500}>
                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clipRule="evenodd" />
                      </svg>
                    )}
                  </div>
                  <span style={{ color: COLORS.zinc400, fontSize: 10 * scale, marginTop: 2 * scale, display: 'block' }}>{type.desc}</span>
                </div>
              );
            })}
          </div>
        </div>
      )}

      {(highlightSection === 'button' || highlightSection === 'all') && (
        <div
          style={{
            borderTop: `1px solid ${COLORS.border5}`,
            paddingTop: 20 * scale,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            opacity: buttonOpacity,
            transition: 'opacity 0.2s ease',
          }}
        >
          <span style={{ color: COLORS.zinc500, fontSize: 13 * scale, display: 'inline-flex', alignItems: 'center', gap: 6 * scale }}>
            <svg width={13 * scale} height={13 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ verticalAlign: 'middle' }}>
              <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="currentColor" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
            Costs 3 credits
          </span>
          <div style={{ position: 'relative' }}>
            {triggerClickRipple && (
              <div
                style={{
                  position: 'absolute',
                  inset: 0,
                  borderRadius: 12 * scale,
                  background: COLORS.emerald400,
                  opacity: 0.2,
                  transform: 'scale(1.5)',
                  filter: 'blur(8px)',
                  transition: 'transform 0.4s ease, opacity 0.4s ease',
                }}
              />
            )}
            <div
              style={{
                background: COLORS.emerald500,
                color: COLORS.bg,
                padding: `${12 * scale}px ${24 * scale}px`,
                borderRadius: 12 * scale,
                fontSize: 15 * scale,
                fontWeight: 600,
                display: 'flex',
                alignItems: 'center',
                gap: 8 * scale,
                boxShadow: `0 0 20px ${COLORS.emerald500}30`,
                transform: triggerClickRipple ? 'scale(0.94)' : 'scale(1)',
                transition: 'transform 0.1s cubic-bezier(0.16, 1, 0.3, 1)',
              }}
            >
              <svg width={16 * scale} height={16 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2.5}>
                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" strokeLinecap="round" strokeLinejoin="round" />
                <path d="M19 10v1a7 7 0 01-14 0v-1M12 18v4M8 22h8" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
              Start Voice Interview
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export const ScoreRing: React.FC<{
  frame: number;
  targetScore: number;
  delay?: number;
  size?: number;
}> = ({ frame, targetScore, delay = 0, size = 200 }) => {
  const { fps } = useVideoConfig();
  const effectiveFrame = Math.max(0, frame - delay);
  const scoreProgress = spring({
    frame: effectiveFrame,
    fps,
    config: { damping: 20, stiffness: 60 },
    durationInFrames: 40,
  });

  const displayScore = Math.round(scoreProgress * targetScore);
  const circumference = Math.PI * (size - 16);
  const dashOffset = interpolate(scoreProgress, [0, 1], [circumference, 0]);

  const scoreColor =
    targetScore >= 80 ? COLORS.emerald400 :
    targetScore >= 60 ? COLORS.yellow400 :
    COLORS.red400;

  return (
    <div style={{ position: 'relative', width: size, height: size }}>
      <svg width={size} height={size} style={{ transform: 'rotate(-90deg)' }}>
        <circle
          cx={size / 2}
          cy={size / 2}
          r={(size - 16) / 2}
          fill="none"
          stroke={COLORS.zinc800}
          strokeWidth={6}
        />
        <circle
          cx={size / 2}
          cy={size / 2}
          r={(size - 16) / 2}
          fill="none"
          stroke={scoreColor}
          strokeWidth={6}
          strokeLinecap="round"
          strokeDasharray={circumference}
          strokeDashoffset={dashOffset}
          style={{ filter: `drop-shadow(0 0 8px ${scoreColor}40)` }}
        />
      </svg>
      <div
        style={{
          position: 'absolute',
          inset: 0,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
        }}
      >
        <span style={{ color: scoreColor, fontSize: size * 0.3, fontWeight: 800, fontFamily: FONT.primary, lineHeight: 1 }}>
          {displayScore}
        </span>
        <span style={{ color: COLORS.zinc500, fontSize: size * 0.08, textTransform: 'uppercase', letterSpacing: '0.15em', fontWeight: 500 }}>
          Score
        </span>
      </div>
    </div>
  );
};

export const CriteriaBar: React.FC<{
  frame: number;
  label: string;
  score: number;
  delay: number;
}> = ({ frame, label, score, delay }) => {
  const { fps } = useVideoConfig();
  const progress = spring({
    frame: Math.max(0, frame - delay),
    fps,
    config: { damping: 18, stiffness: 80 },
  });

  const barWidth = progress * score * 10;
  const barColor = score >= 8 ? COLORS.emerald500 : score >= 6 ? COLORS.yellow400 : COLORS.red400;
  const scale = LAYOUT.width / 1080;

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 4 * scale }}>
        <span style={{ color: COLORS.zinc300, fontSize: 13 * scale, fontWeight: 500 }}>{label}</span>
        <span style={{ color: COLORS.white, fontSize: 13 * scale, fontWeight: 700 }}>{score}/10</span>
      </div>
      <div style={{ background: COLORS.zinc800, borderRadius: 4 * scale, height: 6 * scale, overflow: 'hidden' }}>
        <div
          style={{
            height: '100%',
            width: `${barWidth}%`,
            background: barColor,
            borderRadius: 4 * scale,
            boxShadow: `0 0 6px ${barColor}30`,
          }}
        />
      </div>
    </div>
  );
};

export const LiveIndicator: React.FC<{ frame: number }> = ({ frame }) => {
  const pulse = Math.sin(frame * 0.3) > 0;
  const scale = LAYOUT.width / 1080;

  return (
    <div style={{ display: 'flex', alignItems: 'center', gap: 8 * scale }}>
      <div style={{ position: 'relative', width: 10 * scale, height: 10 * scale }}>
        <div
          style={{
            position: 'absolute',
            inset: 0,
            borderRadius: '50%',
            backgroundColor: COLORS.red400,
            opacity: pulse ? 0.75 : 0,
            transform: `scale(${pulse ? 1.5 : 1})`,
          }}
        />
        <div
          style={{
            position: 'relative',
            width: 10 * scale,
            height: 10 * scale,
            borderRadius: '50%',
            backgroundColor: COLORS.red500,
          }}
        />
      </div>
      <span style={{ color: COLORS.white, fontSize: 14 * scale, fontWeight: 600 }}>Live Interview</span>
    </div>
  );
};
