export const COLORS = {
  bg: '#09090b',
  bgLight: '#18181b',
  bgCard: 'rgba(24,24,27,0.5)',
  emerald400: '#34d399',
  emerald500: '#10b981',
  emerald600: '#059669',
  emerald700: '#047857',
  white: '#ffffff',
  zinc100: '#f4f4f5',
  zinc300: '#d4d4d8',
  zinc400: '#a1a1aa',
  zinc500: '#71717a',
  zinc600: '#52525b',
  zinc700: '#3f3f46',
  zinc800: '#27272a',
  zinc900: '#18181b',
  red400: '#f87171',
  red500: '#ef4444',
  purple400: '#c084fc',
  blue400: '#60a5fa',
  amber400: '#fbbf24',
  amber500: '#f59e0b',
  yellow400: '#facc15',
  surface: 'rgba(255,255,255,0.05)',
  surface10: 'rgba(255,255,255,0.1)',
  border: 'rgba(255,255,255,0.1)',
  border5: 'rgba(255,255,255,0.05)',
} as const;

export const FONT = {
  primary: "'Instrument Sans', 'Inter', system-ui, sans-serif",
  mono: "ui-monospace, 'Cascadia Code', 'Source Code Pro', monospace",
} as const;

export const LAYOUT = {
  width: 1080,
  height: 1920,
  fps: 30,
  duration: 60,
  totalFrames: 1800,
} as const;
