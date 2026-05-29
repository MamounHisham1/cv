import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const IntroShowcase: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // --- Step 1: "What if..." & "AI could be your interviewer?" ---
  // Slide in from frame 5 to 25
  const line1Entrance = spring({ frame: Math.max(0, frame - 5), fps, config: { damping: 16, stiffness: 90 } });
  const line1Opacity = line1Entrance;
  const line1Y = interpolate(line1Entrance, [0, 1], [-40 * scale, 0]);

  const line2Entrance = spring({ frame: Math.max(0, frame - 20), fps, config: { damping: 16, stiffness: 90 } });
  const line2Opacity = line2Entrance;
  const line2Y = interpolate(line2Entrance, [0, 1], [30 * scale, 0]);

  // --- Step 2: Glowing Microphone Circle & "AI Interviewer" Heading ---
  // Slide in from frame 55 to 75
  const micEntrance = spring({ frame: Math.max(0, frame - 55), fps, config: { damping: 14, stiffness: 100 } });
  const micOpacity = micEntrance;
  const micScale = interpolate(micEntrance, [0, 1], [0.6, 1]);

  const headingEntrance = spring({ frame: Math.max(0, frame - 70), fps, config: { damping: 14, stiffness: 90 } });
  const headingOpacity = headingEntrance;
  const headingScale = interpolate(headingEntrance, [0, 1], [1.2, 1]);

  // --- Step 3: Introducing SeratyAI Branding & Tagline ---
  // Slide in from frame 110 to 135
  const brandEntrance = spring({ frame: Math.max(0, frame - 110), fps, config: { damping: 15, stiffness: 85 } });
  const brandOpacity = brandEntrance;
  const brandScale = interpolate(brandEntrance, [0, 1], [0.8, 1]);

  const taglineEntrance = spring({ frame: Math.max(0, frame - 125), fps, config: { damping: 16, stiffness: 80 } });
  const taglineOpacity = taglineEntrance;
  const taglineY = interpolate(taglineEntrance, [0, 1], [20 * scale, 0]);

  // --- Glitch Effect on the very first few frames (analog feel) ---
  const glitchOffset = frame < 4 ? Math.random() * 8 - 4 : 0;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, overflow: 'hidden', padding: 80 * scale }}>
      <OrbSet visible={true} />

      <div
        style={{
          transform: `translateX(${glitchOffset}px)`,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'space-between',
          height: '100%',
          width: '100%',
          zIndex: 1,
          position: 'relative',
        }}
      >
        {/* --- TOP: Cold Open Question --- */}
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16 * scale, marginTop: 40 * scale }}>
          <div
            style={{
              opacity: line1Opacity,
              transform: `translateY(${line1Y}px)`,
              color: COLORS.zinc400,
              fontSize: 28 * scale,
              fontWeight: 500,
              fontFamily: FONT.primary,
              letterSpacing: '0.05em',
            }}
          >
            What if...
          </div>
          <div
            style={{
              opacity: line2Opacity,
              transform: `translateY(${line2Y}px)`,
              color: COLORS.white,
              fontSize: 50 * scale,
              fontWeight: 800,
              fontFamily: FONT.primary,
              letterSpacing: '-1.5px',
              textAlign: 'center',
              lineHeight: 1.2,
            }}
          >
            AI could be
            <br />
            your <span style={{ color: COLORS.emerald400 }}>interviewer</span>?
          </div>
          <div
            style={{
              opacity: line2Opacity,
              height: 3 * scale,
              width: 120 * scale,
              background: `linear-gradient(90deg, transparent, ${COLORS.emerald500}, transparent)`,
              borderRadius: 2,
              marginTop: 10 * scale,
            }}
          />
        </div>

        {/* --- MIDDLE: Microphone Icon & Focus Concept --- */}
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 28 * scale }}>
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
              opacity: micOpacity,
              transform: `scale(${micScale})`,
              boxShadow: `0 0 80px ${COLORS.emerald500}20`,
            }}
          >
            <svg width={60 * scale} height={60 * scale} viewBox="0 0 24 24" fill="none" stroke={COLORS.emerald400} strokeWidth={1.5}>
              <path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>

          <div
            style={{
              opacity: headingOpacity,
              transform: `scale(${headingScale})`,
              textAlign: 'center',
            }}
          >
            <div style={{ color: COLORS.white, fontSize: 52 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-2px', lineHeight: 1.1 }}>
              AI <span style={{ color: COLORS.emerald400 }}>Interviewer</span>
            </div>
          </div>
        </div>

        {/* --- BOTTOM: SeratyAI Branding & Value Proposition --- */}
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 24 * scale, marginBottom: 60 * scale }}>
          <div
            style={{
              opacity: brandOpacity,
              transform: `scale(${brandScale})`,
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
            }}
          >
            <div style={{ fontSize: 16 * scale, color: COLORS.emerald400, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.2em', textTransform: 'uppercase', marginBottom: 8 * scale }}>
              Introducing
            </div>
            <div style={{ color: COLORS.white, fontSize: 58 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-2.5px' }}>
              Seraty<span style={{ color: COLORS.emerald400 }}>AI</span>
            </div>
          </div>
          <div
            style={{
              opacity: brandOpacity,
              height: 2 * scale,
              width: 200 * scale,
              background: `linear-gradient(90deg, transparent, ${COLORS.emerald500}, transparent)`,
            }}
          />
          <div
            style={{
              opacity: taglineOpacity,
              transform: `translateY(${taglineY}px)`,
              color: COLORS.zinc400,
              fontSize: 20 * scale,
              fontFamily: FONT.primary,
              textAlign: 'center',
              maxWidth: 700 * scale,
              lineHeight: 1.5,
            }}
          >
            Voice-based mock interviews
            <br />
            that adapt to your CV.
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
