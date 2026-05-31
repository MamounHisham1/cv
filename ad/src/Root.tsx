import React from 'react';
import { Composition, registerRoot } from 'remotion';
import { SeratyAd } from './SeratyAd';
import { SeratyAdArabic } from './SeratyAdArabic';
import { SeratyAdV2 } from './SeratyAdV2';
import { SeratyAdArabicV2 } from './SeratyAdArabicV2';
import { LAYOUT, LAYOUT_V2, LAYOUT_V2_AR } from './styles';

const RemotionRoot: React.FC = () => {
  return (
    <>
      <Composition
        id="SeratyAd"
        component={SeratyAd}
        durationInFrames={LAYOUT.totalFrames}
        fps={LAYOUT.fps}
        width={LAYOUT.width}
        height={LAYOUT.height}
      />
      <Composition
        id="SeratyAdArabic"
        component={SeratyAdArabic}
        durationInFrames={LAYOUT.totalFrames}
        fps={LAYOUT.fps}
        width={LAYOUT.width}
        height={LAYOUT.height}
      />
      <Composition
        id="SeratyAdV2"
        component={SeratyAdV2}
        durationInFrames={LAYOUT_V2.totalFrames}
        fps={LAYOUT_V2.fps}
        width={LAYOUT_V2.width}
        height={LAYOUT_V2.height}
      />
      <Composition
        id="SeratyAdArabicV2"
        component={SeratyAdArabicV2}
        durationInFrames={LAYOUT_V2_AR.totalFrames}
        fps={LAYOUT_V2_AR.fps}
        width={LAYOUT_V2_AR.width}
        height={LAYOUT_V2_AR.height}
      />
    </>
  );
};

registerRoot(RemotionRoot);
