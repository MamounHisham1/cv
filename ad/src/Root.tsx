import React from 'react';
import { Composition, registerRoot } from 'remotion';
import { SeratyAd } from './SeratyAd';
import { LAYOUT } from './styles';

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
    </>
  );
};

registerRoot(RemotionRoot);
