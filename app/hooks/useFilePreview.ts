import { useEffect, useState } from "react";

export default function useFilePreview(
  file: FileList | null, defaultPreview: string | null | undefined
): [string | null, React.Dispatch<React.SetStateAction<string | null>>] {
  const [imgSrc, setImgSrc] = useState<string | null>(null);

  useEffect(() => {

    if (file && file[0]) {
      const newUrl = URL.createObjectURL(file[0]);
      if (newUrl !== imgSrc) {
        setImgSrc(newUrl);
      }
    } else if(defaultPreview) {
      setImgSrc(defaultPreview);
    }
  }, [file]);

  return [imgSrc, setImgSrc];
}
