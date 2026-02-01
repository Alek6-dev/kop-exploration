import React from 'react';
import { InfinitySpin } from 'react-loader-spinner';

const Loader = () => {
  return (
    <div className="fixed w-screen h-screen left-0 top-0 z-100">
        <InfinitySpin
            width="200"
            color="#4fa94d"
        />
    </div>
  );
};

export default Loader;
