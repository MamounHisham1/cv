import React from 'react';
import { staticFile } from 'remotion';
import { COLORS, FONT } from '../styles';

interface SeratyLogoProps {
  size?: 'small' | 'medium' | 'large';
  showTagline?: boolean;
}

export const SeratyLogo: React.FC<SeratyLogoProps> = ({
  size = 'medium',
  showTagline = false,
}) => {
  const sizes = {
    small: { logo: 36, icon: 80 },
    medium: { logo: 52, icon: 120 },
    large: { logo: 72, icon: 160 },
  };

  const s = sizes[size];

  return (
    <div
      style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        gap: 24,
      }}
    >
      {/* Actual logo image */}
      <img
        src={staticFile('logo.png')}
        style={{
          width: s.icon,
          height: s.icon,
          objectFit: 'contain',
          filter: `drop-shadow(0 0 40px ${COLORS.emerald500}40)`,
        }}
      />

      {/* Wordmark */}
      <div style={{ display: 'flex', alignItems: 'baseline', gap: 4 }}>
        <span
          style={{
            fontFamily: FONT.primary,
            fontSize: s.logo,
            fontWeight: 700,
            color: COLORS.white,
            letterSpacing: -1,
          }}
        >
          Seraty
        </span>
        <span
          style={{
            fontFamily: FONT.primary,
            fontSize: s.logo,
            fontWeight: 700,
            color: COLORS.emerald400,
            letterSpacing: -1,
          }}
        >
          AI
        </span>
      </div>

      {/* Tagline */}
      {showTagline && (
        <div
          style={{
            fontFamily: FONT.primary,
            fontSize: s.logo * 0.32,
            color: COLORS.zinc400,
            textAlign: 'center',
            maxWidth: 600,
            lineHeight: 1.5,
          }}
        >
          Build, Enhance & Evaluate Your CV — All with AI
        </div>
      )}
    </div>
  );
};
