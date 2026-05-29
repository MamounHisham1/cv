import React from 'react';
import { useCurrentFrame, useVideoConfig, interpolate, spring } from 'remotion';
import { COLORS, FONT, LAYOUT } from '../styles';

export const SplitTransition: React.FC<{
  frame: number;
  direction: 'left' | 'right' | 'up' | 'down';
  color?: string;
  duration?: number;
  children: React.ReactNode;
}> = ({ frame, direction, color = COLORS.emerald500, duration = 15, children }) => {
  const progress = interpolate(frame, [0, duration], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  const isVertical = direction === 'up' || direction === 'down';
  const isPositive = direction === 'right' || direction === 'down';

  const wipeSize = interpolate(progress, [0, 0.5, 1], [0, 100, 100], {
    extrapolateRight: 'clamp',
  });

  const contentOpacity = interpolate(progress, [0.3, 0.6], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  return (
    <div style={{ position: 'relative', width: '100%', height: '100%', overflow: 'hidden' }}>
      <div style={{ opacity: contentOpacity }}>{children}</div>
      <div
        style={{
          position: 'absolute',
          backgroundColor: color,
          [isVertical ? 'height' : 'width']: `${wipeSize}%`,
          [isVertical ? 'width' : 'height']: '100%',
          ...(isVertical
            ? { left: 0, [direction]: 0 }
            : { top: 0, [direction]: 0 }),
          transition: 'none',
        }}
      />
    </div>
  );
};

export const GlitchTransition: React.FC<{
  frame: number;
  children: React.ReactNode;
}> = ({ frame, children }) => {
  const intensity = frame < 8 ? 1 - frame / 8 : 0;
  const opacity = interpolate(frame, [0, 5], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  const offsetX = intensity * (Math.random() > 0.5 ? 8 : -8);
  const offsetY = intensity * (Math.random() > 0.5 ? 4 : -4);

  return (
    <div
      style={{
        opacity,
        transform: `translate(${offsetX}px, ${offsetY}px)`,
      }}
    >
      {children}
    </div>
  );
};

export const SlideTransition: React.FC<{
  frame: number;
  from: 'left' | 'right' | 'bottom' | 'top';
  duration?: number;
  children: React.ReactNode;
}> = ({ frame, from, duration = 12, children }) => {
  const { fps } = useVideoConfig();
  const progress = spring({
    frame,
    fps,
    config: { damping: 20, stiffness: 120 },
    durationInFrames: duration,
  });

  const translates = {
    left: [-LAYOUT.width, 0],
    right: [LAYOUT.width, 0],
    bottom: [0, LAYOUT.height],
    top: [0, -LAYOUT.height],
  };

  const [startX, startY] = translates[from];
  const x = interpolate(progress, [0, 1], [startX, 0]);
  const y = interpolate(progress, [0, 1], [startY, 0]);

  return <div style={{ transform: `translate(${x}px, ${y}px)` }}>{children}</div>;
};

export const FadeIn: React.FC<{
  frame: number;
  delay?: number;
  duration?: number;
  children: React.ReactNode;
}> = ({ frame, delay = 0, duration = 10, children }) => {
  const opacity = interpolate(frame, [delay, delay + duration], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });
  return <div style={{ opacity }}>{children}</div>;
};

export const ScaleIn: React.FC<{
  frame: number;
  delay?: number;
  children: React.ReactNode;
}> = ({ frame, delay = 0, children }) => {
  const { fps } = useVideoConfig();
  const scale = spring({
    frame: Math.max(0, frame - delay),
    fps,
    config: { damping: 14, stiffness: 100 },
  });
  return <div style={{ transform: `scale(${scale})` }}>{children}</div>;
};

export const TypewriterText: React.FC<{
  frame: number;
  text: string;
  delay?: number;
  speed?: number;
  style?: React.CSSProperties;
}> = ({ frame, text, delay = 0, speed = 2, style }) => {
  const chars = Math.floor(Math.max(0, frame - delay) / speed);
  const visibleText = text.slice(0, Math.min(chars, text.length));
  const showCursor = frame % 20 < 10;

  return (
    <span style={{ ...style }}>
      {visibleText}
      {chars < text.length && showCursor && (
        <span style={{ color: COLORS.emerald400 }}>|</span>
      )}
    </span>
  );
};

export const ZoomPan: React.FC<{
  frame: number;
  totalFrames: number;
  panDirection?: 'up' | 'down' | 'left' | 'right';
  zoomRange?: [number, number];
  children: React.ReactNode;
}> = ({ frame, totalFrames, panDirection = 'up', zoomRange = [1, 1.15], children }) => {
  const progress = frame / totalFrames;
  const scale = interpolate(progress, [0, 1], zoomRange);

  const panX = panDirection === 'left' ? interpolate(progress, [0, 1], [30, -30]) :
               panDirection === 'right' ? interpolate(progress, [0, 1], [-30, 30]) : 0;
  const panY = panDirection === 'up' ? interpolate(progress, [0, 1], [30, -30]) :
               panDirection === 'down' ? interpolate(progress, [0, 1], [-30, 30]) : 0;

  return (
    <div style={{ transform: `scale(${scale}) translate(${panX}px, ${panY}px)`, transformOrigin: 'center center' }}>
      {children}
    </div>
  );
};

export const DiagonalSplit: React.FC<{
  frame: number;
  duration?: number;
  children: React.ReactNode;
}> = ({ frame, duration = 15, children }) => {
  const progress = interpolate(frame, [0, duration], [110, 0], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  return (
    <div style={{ position: 'relative', width: '100%', height: '100%', overflow: 'hidden' }}>
      <div style={{ opacity: interpolate(frame, [duration * 0.3, duration * 0.7], [0, 1], { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }) }}>
        {children}
      </div>
      <div
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          height: `${progress}%`,
          background: `linear-gradient(135deg, ${COLORS.bg} 40%, ${COLORS.emerald500} 100%)`,
        }}
      />
    </div>
  );
};
