# Remotion Advertisement Video — SeratyAI

## Goal
30-second social media ad (9:16 vertical, 1080×1920, 30fps) for LinkedIn, Instagram Reels, TikTok, and YouTube.

## Narrative: Problem → Solution
Professional & inspiring tone. Dark glassmorphism aesthetic matching the app.

## Scene Breakdown (900 frames / 30s)

| Scene | Time | Frames | Description |
|-------|------|--------|-------------|
| Hook | 0-5s | 0-150 | Messy old CV, "Still sending THIS?" zoom into bad formatting |
| Discovery | 5-10s | 150-300 | Old CV out, gradient orbs animate in, SeratyAI logo reveals |
| Build | 10-18s | 300-540 | Template flip → AI chat typing → live preview updating |
| Result | 18-25s | 540-750 | Beautiful CV in Creative template, "ATS Score: 96" badge |
| CTA | 25-30s | 750-900 | Logo + tagline + "Get Started Free" button + URL |

## Tech Stack
- Remotion v4, React, TypeScript
- Font: Instrument Sans
- Output: MP4 via `npx remotion render`

## Color Palette
- Background: #09090b (zinc-950)
- Accent: #34d399 / #10b981 (emerald)
- Surface: rgba(255,255,255,0.05) with backdrop blur
- Orbs: emerald, purple, blue gradients
- Text: white / zinc-100

## Structure
```
ad/
├── src/
│   ├── Root.tsx
│   ├── SeratyAd.tsx
│   ├── scenes/ (Hook, Discovery, Build, Result, CTA)
│   ├── components/ (GradientOrb, FakeCV, TemplateFlip, AiChatBubble, ATSOverlay, SeratyLogo)
│   └── styles.ts
└── package.json
```
