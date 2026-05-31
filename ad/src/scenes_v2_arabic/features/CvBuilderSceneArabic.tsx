import React from 'react';
import { AbsoluteFill, useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS } from '../../styles';

const ARABIC_FONT = "'Cairo', 'Tajawal', sans-serif";

export const CvBuilderSceneArabic: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const templateEntrance = spring({ frame: Math.max(0, frame - 15), fps, config: { damping: 16, stiffness: 90 } });
  const templateX = interpolate(templateEntrance, [0, 1], [120, 0]);
  const templateOpacity = interpolate(templateEntrance, [0, 1], [0, 1]);

  const panelEntrance = spring({ frame: Math.max(0, frame - 40), fps, config: { damping: 16, stiffness: 90 } });
  const panelOpacity = interpolate(panelEntrance, [0, 1], [0, 1]);
  const aiPanelSlide = interpolate(frame, [40, 75], [50, 0], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });

  const typingSpeed = 9;
  const jobTitleChars = Math.floor(Math.max(0, frame - 80) / typingSpeed);
  const jobTitle = 'Senior Product Designer';
  const visibleTitle = jobTitle.slice(0, Math.min(jobTitleChars, jobTitle.length));

  const descChars = Math.floor(Math.max(0, frame - 120) / typingSpeed);
  const description = 'Led cross-functional design teams to deliver high-impact product experiences across multiple platforms, driving a 40% increase in user engagement.';
  const visibleDesc = description.slice(0, Math.min(descChars, description.length));

  const tag1Entrance = spring({ frame: Math.max(0, frame - 180), fps, config: { damping: 14, stiffness: 100 } });
  const tag2Entrance = spring({ frame: Math.max(0, frame - 215), fps, config: { damping: 14, stiffness: 100 } });
  const tag3Entrance = spring({ frame: Math.max(0, frame - 250), fps, config: { damping: 14, stiffness: 100 } });

  const cursorVisible = frame % 20 < 10;

  return (
    <AbsoluteFill style={{ backgroundColor: COLORS.bg, overflow: 'hidden', padding: 50 }}>
      <div style={{ display: 'flex', flexDirection: 'row', gap: 20, height: '100%', alignItems: 'stretch' }}>
        <div
          style={{
            flex: 1,
            opacity: templateOpacity,
            transform: `translateX(${templateX}px)`,
            background: `linear-gradient(135deg, ${COLORS.bgLight}, ${COLORS.zinc900})`,
            borderRadius: 24,
            border: `1px solid ${COLORS.border}`,
            padding: '48px 40px',
            display: 'flex',
            flexDirection: 'column',
            gap: 16,
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 20, marginBottom: 24 }}>
            <div style={{ width: 60, height: 60, borderRadius: '50%', background: COLORS.emerald500, opacity: 0.3 }} />
            <div>
              <div style={{ width: 200, height: 18, borderRadius: 4, background: COLORS.zinc700, marginBottom: 8 }} />
              <div style={{ width: 130, height: 14, borderRadius: 4, background: COLORS.zinc700 }} />
            </div>
          </div>

          <div style={{ fontSize: 32, fontWeight: 700, fontFamily: ARABIC_FONT, color: COLORS.white, lineHeight: 1.3, direction: 'rtl' }}>
            {visibleTitle}
            {jobTitleChars < jobTitle.length && cursorVisible && (
              <span style={{ color: COLORS.emerald400 }}>|</span>
            )}
          </div>

          <div style={{ fontSize: 22, fontWeight: 400, fontFamily: ARABIC_FONT, color: COLORS.zinc400, lineHeight: 1.5, marginBottom: 20, direction: 'rtl' }}>
            {visibleDesc}
            {descChars < description.length && cursorVisible && (
              <span style={{ color: COLORS.emerald400 }}>|</span>
            )}
          </div>

          {[0, 1, 2].map((i) => {
            const bulletFade = interpolate(frame, [140 + i * 20, 160 + i * 20], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' });
            return (
              <div
                key={i}
                style={{
                  opacity: bulletFade,
                  display: 'flex',
                  gap: 16,
                  alignItems: 'flex-start',
                  marginBottom: 12,
                  direction: 'rtl',
                }}
              >
                <div style={{ width: 8, height: 8, borderRadius: '50%', background: COLORS.emerald400, flexShrink: 0, marginTop: 8 }} />
                <div style={{ width: `${75 - i * 12}%`, height: 14, borderRadius: 4, background: COLORS.zinc700 }} />
              </div>
            );
          })}
        </div>

        <div
          style={{
            width: 320,
            opacity: panelOpacity,
            transform: `translateY(${aiPanelSlide}px)`,
            background: `linear-gradient(135deg, ${COLORS.zinc900}, ${COLORS.bg})`,
            borderRadius: 24,
            border: `1px solid ${COLORS.emerald500}30`,
            padding: '36px 28px',
            display: 'flex',
            flexDirection: 'column',
            gap: 20,
          }}
        >
          <div style={{ fontSize: 20, fontWeight: 700, fontFamily: ARABIC_FONT, color: COLORS.emerald400, letterSpacing: '0.05em', direction: 'rtl' }}>
            اقتراحات الذكاء الاصطناعي
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '16px 18px', background: 'rgba(16, 185, 129, 0.08)', borderRadius: 14, border: '1px solid rgba(16, 185, 129, 0.15)', direction: 'rtl' }}>
            <span style={{ fontSize: 22 }}>+</span>
            <span style={{ fontSize: 20, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc300 }}>مقاييس تأثير قابلة للقياس</span>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '16px 18px', background: 'rgba(16, 185, 129, 0.08)', borderRadius: 14, border: '1px solid rgba(16, 185, 129, 0.15)', direction: 'rtl' }}>
            <span style={{ fontSize: 22 }}>#</span>
            <span style={{ fontSize: 20, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc300 }}>تحسين كلمات ATS</span>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: 14, padding: '16px 18px', background: 'rgba(16, 185, 129, 0.08)', borderRadius: 14, border: '1px solid rgba(16, 185, 129, 0.15)', direction: 'rtl' }}>
            <span style={{ fontSize: 22 }}>T</span>
            <span style={{ fontSize: 20, fontWeight: 500, fontFamily: ARABIC_FONT, color: COLORS.zinc300 }}>قوالب عصرية</span>
          </div>

          <div style={{ marginTop: 'auto', display: 'flex', flexWrap: 'wrap', gap: 10 }}>
            <div
              style={{
                opacity: tag1Entrance,
                transform: `scale(${tag1Entrance})`,
                background: 'rgba(16, 185, 129, 0.12)',
                borderRadius: 10,
                padding: '8px 18px',
                fontSize: 18,
                fontWeight: 600,
                fontFamily: ARABIC_FONT,
                color: COLORS.emerald400,
              }}
            >
              متوافق مع ATS ✓
            </div>
            <div
              style={{
                opacity: tag2Entrance,
                transform: `scale(${tag2Entrance})`,
                background: 'rgba(16, 185, 129, 0.12)',
                borderRadius: 10,
                padding: '8px 18px',
                fontSize: 18,
                fontWeight: 600,
                fontFamily: ARABIC_FONT,
                color: COLORS.emerald400,
              }}
            >
              اقتراحات ذكية ✓
            </div>
            <div
              style={{
                opacity: tag3Entrance,
                transform: `scale(${tag3Entrance})`,
                background: 'rgba(16, 185, 129, 0.12)',
                borderRadius: 10,
                padding: '8px 18px',
                fontSize: 18,
                fontWeight: 600,
                fontFamily: ARABIC_FONT,
                color: COLORS.emerald400,
              }}
            >
              قوالب عصرية ✓
            </div>
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
};
