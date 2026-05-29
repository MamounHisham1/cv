import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { FeatureRecapCard } from '../../components/FeatureRecap';
import { OrbSet } from '../../components/GradientOrb';
import { COLORS, FONT, LAYOUT } from '../../styles';

export const FeaturesListShowcase: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  const scale = LAYOUT.width / 1080;

  // Title entrance (frame 0 to 20)
  const titleEntrance = spring({ frame, fps, config: { damping: 18, stiffness: 90 } });
  const titleOpacity = titleEntrance;
  const titleY = interpolate(titleEntrance, [0, 1], [-20 * scale, 0]);

  // Features dataset to render stacked cards
  const features = [
    {
      id: 'builder',
      delay: 0,
      icon: (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
          <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      ),
      title: 'AI-Powered Builder',
      description: 'Build professional CVs with AI assistance. Smart suggestions, real-time preview, and instant formatting.',
      accentColor: COLORS.emerald400,
    },
    {
      id: 'templates',
      delay: 35,
      icon: (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" strokeLinecap="round" strokeLinejoin="round" />
          <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      ),
      title: '10 Professional Templates',
      description: 'From classic to creative, find the perfect template. ATS-optimized and recruiter-approved.',
      accentColor: COLORS.blue400,
    },
    {
      id: 'ats',
      delay: 70,
      icon: (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
          <rect x="4" y="4" width="16" height="16" rx="2" strokeLinecap="round" strokeLinejoin="round" />
          <path d="M9 9H15V15H9V9Z" strokeLinecap="round" strokeLinejoin="round" />
          <path d="M9 1V4M15 1V4M9 20V23M15 20V23M20 9H23M20 15H23M1 9H4M1 15H4" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      ),
      title: 'ATS Optimization',
      description: 'AI evaluates your CV against 17K+ resume benchmarks. Score high, get noticed.',
      accentColor: COLORS.purple400,
    },
    {
      id: 'evaluator',
      delay: 105,
      icon: (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
          <path d="M18 20V10M12 20V4M6 20v-6" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      ),
      title: 'AI Evaluator',
      description: 'Get detailed feedback on your CV. 10 evaluation criteria with actionable insights.',
      accentColor: COLORS.amber400,
    },
    {
      id: 'interviewer',
      delay: 140,
      icon: (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} style={{ width: '100%', height: '100%' }}>
          <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" strokeLinecap="round" strokeLinejoin="round" />
          <path d="M19 10v1a7 7 0 01-14 0v-1M12 18v4M8 22h8" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      ),
      title: 'AI Interviewer',
      description: 'Practice with voice-based mock interviews. Get scored on 6 criteria with detailed feedback.',
      accentColor: COLORS.emerald400,
    },
  ];

  // Upward scroll at the end of scene to transition nicely (from frame 260 to 300)
  const scrollProgress = spring({ frame: Math.max(0, frame - 260), fps, config: { damping: 20, stiffness: 70 } });
  const scrollY = interpolate(scrollProgress, [0, 1], [0, -180 * scale]);
  const listFadeOut = interpolate(scrollProgress, [0, 1], [1, 0]);

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, justifyContent: 'flex-start', alignItems: 'center', paddingTop: 130 * scale, overflow: 'hidden' }}>
      <OrbSet visible={true} />

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 32 * scale, zIndex: 1, width: '100%', opacity: listFadeOut, transform: `translateY(${scrollY}px)` }}>
        
        {/* --- HEADER --- */}
        <div style={{ opacity: titleOpacity, transform: `translateY(${titleY}px)`, textAlign: 'center', marginBottom: 12 * scale }}>
          <span style={{ color: COLORS.emerald400, fontSize: 13 * scale, fontWeight: 600, fontFamily: FONT.primary, letterSpacing: '0.15em', textTransform: 'uppercase', display: 'block', marginBottom: 8 * scale }}>
            Full CV Toolkit
          </span>
          <h2 style={{ color: COLORS.white, fontSize: 34 * scale, fontWeight: 800, fontFamily: FONT.primary, letterSpacing: '-1.5px', lineHeight: 1.2 }}>
            Everything You Need <br /> To <span style={{ color: COLORS.emerald400 }}>Succeed</span>
          </h2>
        </div>

        {/* --- CASCADING VERTICAL LIST --- */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 20 * scale, width: '100%', alignItems: 'center' }}>
          {features.map((feat) => {
            // Sequential slide-in from the right
            const entrance = spring({
              frame: Math.max(0, frame - feat.delay),
              fps,
              config: { damping: 16, stiffness: 100 },
            });
            const slideX = interpolate(entrance, [0, 1], [LAYOUT.width, 0]);

            return (
              <div
                key={feat.id}
                style={{
                  transform: `translateX(${slideX}px)`,
                  opacity: entrance,
                  width: '100%',
                  display: 'flex',
                  justifyContent: 'center',
                }}
              >
                <FeatureRecapCard
                  frame={frame}
                  icon={feat.icon}
                  title={feat.title}
                  description={feat.description}
                  accentColor={feat.accentColor}
                  delay={feat.delay}
                />
              </div>
            );
          })}
        </div>

      </div>
    </AbsoluteFill>
  );
};
