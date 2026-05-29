import React from 'react';
import {
  AbsoluteFill,
  interpolate,
  useCurrentFrame,
} from 'remotion';
import { COLORS } from '../styles';

interface GradientOrbProps {
  x: number;
  y: number;
  size: number;
  color: string;
  opacity?: number;
  speed?: number;
  offset?: number;
}

export const GradientOrb: React.FC<GradientOrbProps> = ({
  x,
  y,
  size,
  color,
  opacity = 0.3,
  speed = 0.02,
  offset = 0,
}) => {
  const frame = useCurrentFrame();
  const floatX = Math.sin((frame + offset) * speed) * 40;
  const floatY = Math.cos((frame + offset) * speed * 0.7) * 30;

  return (
    <AbsoluteFill>
      <div
        style={{
          position: 'absolute',
          left: x + floatX - size / 2,
          top: y + floatY - size / 2,
          width: size,
          height: size,
          borderRadius: '50%',
          background: color,
          opacity,
          filter: `blur(${size * 0.3}px)`,
        }}
      />
    </AbsoluteFill>
  );
};

export const OrbSet: React.FC<{ visible: boolean }> = ({ visible }) => {
  const frame = useCurrentFrame();
  const opacity = interpolate(frame, [0, 30], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  if (!visible) return null;

  return (
    <AbsoluteFill style={{ opacity }}>
      <GradientOrb x={200} y={400} size={500} color={COLORS.emerald500} opacity={0.2} speed={0.015} offset={0} />
      <GradientOrb x={800} y={600} size={400} color={COLORS.purple400} opacity={0.15} speed={0.02} offset={100} />
      <GradientOrb x={500} y={1200} size={350} color={COLORS.blue400} opacity={0.12} speed={0.018} offset={200} />
      <GradientOrb x={150} y={1500} size={300} color={COLORS.emerald400} opacity={0.1} speed={0.025} offset={50} />
    </AbsoluteFill>
  );
};
