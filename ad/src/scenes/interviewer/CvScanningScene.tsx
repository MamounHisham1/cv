import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const CvScanningScene: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Title entrance (frame 5 to 25)
  const titleEntrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 90 } });
  const titleOpacity = titleEntrance;
  const titleY = interpolate(titleEntrance, [0, 1], [-20 * scale, 0]);

  // CV float entrance (frame 20 to 50)
  const cvEntrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 18, stiffness: 80 } });
  const cvScale = interpolate(cvEntrance, [0, 1], [0.7, 1]);
  const cvOpacity = interpolate(frame, [20, 35], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const cvY = interpolate(cvEntrance, [0, 1], [80 * scale, 0]);

  // Laser scanning sweep line (frame 40 to 90)
  const scanProgress = interpolate(frame, [40, 95], [-10, 100], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
  const scanLineOpacity = interpolate(frame, [40, 48, 90, 95], [0, 1, 1, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  // Recruiter speech card reveal (frame 95 to 120)
  const cardEntrance = spring({ frame: Math.max(0, frame - 95), fps, config: { damping: 14, stiffness: 85 } });
  const cardScale = interpolate(cardEntrance, [0, 1], [0.8, 1]);
  const cardOpacity = cardEntrance;
  const cardY = interpolate(cardEntrance, [0, 1], [40 * scale, 0]);

  // Fading out CV when card reveals
  const cvDisplayOpacity = interpolate(frame, [95, 110], [1, 0.05], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', overflow: 'hidden', paddingTop: 130 * scale, paddingLeft: 80 * scale, paddingRight: 80 * scale }}>
      <OrbSet visible={true} />

      {/* --- TOP: Title --- */}
      <div style={{ opacity: titleOpacity, transform: `translateY(${titleY}px)`, textAlign: 'center', marginBottom: 50 * scale, zIndex: 10 }}>
        <span style={{ color: COLORS.emerald400, fontSize: 13 * scale, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.15em', textTransform: 'uppercase', display: 'block', marginBottom: 8 * scale }}>
          Tailored To You
        </span>
        <h2 style={{ color: COLORS.white, fontSize: 36 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-1.5px', lineHeight: 1.2 }}>
          CV-Tailored Questions
        </h2>
      </div>

      <div style={{ position: 'relative', width: '100%', height: 1100 * scale, display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 1 }}>
        
        {/* --- DYNAMIC CV MOCKUP --- */}
        <div
          style={{
            position: 'absolute',
            width: LAYOUT.width - 240 * scale,
            height: 620 * scale,
            background: COLORS.bgCard,
            border: `1.5px solid ${COLORS.border}`,
            borderRadius: 20 * scale,
            padding: 32 * scale,
            boxShadow: '0 20px 50px rgba(0,0,0,0.6)',
            transform: `scale(${cvScale}) translateY(${cvY}px)`,
            opacity: cvOpacity * cvDisplayOpacity,
            display: 'flex',
            flexDirection: 'column',
            gap: 20 * scale,
            overflow: 'hidden',
          }}
        >
          {/* Header block */}
          <div style={{ display: 'flex', gap: 16 * scale, alignItems: 'center' }}>
            <div style={{ width: 48 * scale, height: 48 * scale, borderRadius: 10 * scale, background: `${COLORS.emerald500}25`, border: `1px solid ${COLORS.emerald500}40` }} />
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 * scale }}>
              <div style={{ width: 180 * scale, height: 14 * scale, background: COLORS.white, borderRadius: 4 }} />
              <div style={{ width: 120 * scale, height: 10 * scale, background: COLORS.zinc500, borderRadius: 4 }} />
            </div>
          </div>

          <hr style={{ border: 'none', borderTop: `1px solid ${COLORS.border}` }} />

          {/* Skeleton experience items */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 * scale }}>
            <div style={{ width: 220 * scale, height: 12 * scale, background: COLORS.zinc400, borderRadius: 4 }} />
            <div style={{ width: '100%', height: 10 * scale, background: COLORS.zinc600, borderRadius: 4 }} />
            <div style={{ width: '92%', height: 10 * scale, background: COLORS.zinc600, borderRadius: 4 }} />
            <div style={{ width: '60%', height: 10 * scale, background: `${COLORS.emerald500}40`, borderRadius: 4, border: `1.5px solid ${COLORS.emerald500}60` }} /> {/* Highlighted match */}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 * scale, marginTop: 12 * scale }}>
            <div style={{ width: 160 * scale, height: 12 * scale, background: COLORS.zinc400, borderRadius: 4 }} />
            <div style={{ width: '96%', height: 10 * scale, background: COLORS.zinc600, borderRadius: 4 }} />
            <div style={{ width: '85%', height: 10 * scale, background: COLORS.zinc600, borderRadius: 4 }} />
          </div>

          {/* Glowing laser scanning sweep line */}
          {scanLineOpacity > 0 && (
            <div
              style={{
                position: 'absolute',
                top: `${scanProgress}%`,
                left: 0,
                right: 0,
                height: 4 * scale,
                background: `linear-gradient(90deg, transparent, ${COLORS.emerald400}, transparent)`,
                boxShadow: `0 0 16px ${COLORS.emerald400}, 0 0 32px ${COLORS.emerald500}80`,
                opacity: scanLineOpacity,
                zIndex: 5,
              }}
            />
          )}
        </div>

        {/* --- RECRUITER SPEECH CARD --- */}
        {cardOpacity > 0 && (
          <div
            style={{
              position: 'absolute',
              width: LAYOUT.width - 160 * scale,
              background: 'rgba(20, 20, 23, 0.75)',
              backdropFilter: 'blur(36px)',
              border: `1.5px solid ${COLORS.border}`,
              borderRadius: 24 * scale,
              padding: 40 * scale,
              boxShadow: `0 30px 60px rgba(0,0,0,0.8), 0 0 40px ${COLORS.emerald500}10`,
              transform: `scale(${cardScale}) translateY(${cardY}px)`,
              opacity: cardOpacity,
              display: 'flex',
              flexDirection: 'column',
              gap: 24 * scale,
            }}
          >
            {/* Recruiter avatar indicator */}
            <div style={{ display: 'flex', alignItems: 'center', gap: 16 * scale }}>
              <div
                style={{
                  width: 48 * scale,
                  height: 48 * scale,
                  borderRadius: '50%',
                  background: `${COLORS.emerald500}15`,
                  border: `1px solid ${COLORS.emerald500}30`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: COLORS.emerald400,
                }}
              >
                <svg width={20 * scale} height={20 * scale} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2}>
                  <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" strokeLinecap="round" strokeLinejoin="round"/>
                </svg>
              </div>
              <div>
                <div style={{ color: COLORS.white, fontSize: 15 * scale, fontWeight: 700, fontFamily: FONT.primary }}>AI Recruiter</div>
                <div style={{ color: COLORS.zinc500, fontSize: 11 * scale, textTransform: 'uppercase', letterSpacing: '0.05em', marginTop: 2 * scale }}>Tailored Question</div>
              </div>
            </div>

            <p style={{ color: COLORS.white, fontSize: 24 * scale, fontWeight: 600, fontFamily: FONT.primary, lineHeight: 1.5, fontStyle: 'italic', letterSpacing: '-0.3px', margin: 0 }}>
              "Your CV highlights major work with scaled databases. Tell me about a time you resolved a critical production failure under pressure."
            </p>
          </div>
        )}

      </div>

      {/* --- BOTTOM DESCRIPTION --- */}
      <div
        style={{
          position: 'absolute',
          bottom: 80 * scale,
          left: 80 * scale,
          right: 80 * scale,
          textAlign: 'center',
          opacity: titleOpacity,
          zIndex: 10,
        }}
      >
        <p style={{ color: COLORS.zinc400, fontSize: 16 * scale, fontFamily: FONT.primary, lineHeight: 1.6, maxWidth: 640 * scale, margin: '0 auto' }}>
          Seraty extracts key milestones from your resume to challenge you with real, customized recruiter questions.
        </p>
      </div>
    </AbsoluteFill>
  );
};
