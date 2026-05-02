"use client";

import { useEffect, useRef } from "react";

interface HorizontalScrollProps {
  className?: string;
  children: React.ReactNode;
}

const HorizontalScroll = ({ className, children }: HorizontalScrollProps) => {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const onWheel = (e: WheelEvent) => {
      if (e.deltaY === 0) return;
      e.preventDefault();
      el.scrollLeft += e.deltaY;
    };

    el.addEventListener("wheel", onWheel, { passive: false });
    return () => el.removeEventListener("wheel", onWheel);
  }, []);

  return (
    <div ref={ref} className={className} style={{ scrollbarWidth: "none" }}>
      {children}
    </div>
  );
};

export { HorizontalScroll };
