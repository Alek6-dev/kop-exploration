"use client";

import Image from "next/image";
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "../ui/form";
import { Button } from "../ui/button";
import { Input } from "@/components/ui/input";
import language from "@/messages/fr";
import useFilePreview from "@/hooks/useFilePreview";

interface ImageUploadProps {
  form: any;
  description: string;
  fieldName: string;
  defaultPreview?: string|null;
}

const ImageUpload = ({
  form,
  description,
  fieldName,
  defaultPreview,
}: ImageUploadProps) => {
  const { register, control, watch } = form;

  const fileRef = register(fieldName);
  const image = watch(fieldName);
  const [filePreview] = useFilePreview(image, defaultPreview);

  return (
    <div className="justify-between mb-6 flex-v-centering">
      <div className="block pr-6">
        <div className="mb-4 label">
          {language.registration.form.field.image.pre_label}
        </div>
        <FormField
          control={control}
          name={fieldName}
          render={({ field }) => (
            <FormItem>
              <Button variant="light" size="sm" asChild>
                <FormLabel className="cursor-pointer">
                  {language.registration.form.field.image.label}
                </FormLabel>
              </Button>
              <FormControl>
                <Input
                  type="file"
                  className="hidden"
                  placeholder="photo"
                  {...fileRef}
                />
              </FormControl>
              <FormDescription>{description}</FormDescription>
              <FormMessage />
            </FormItem>
          )}
        />
      </div>

      <div className="w-16 h-16 flex-shrink-0 rounded-full overflow-hidden relative upload-avatar-placeholder">
        {filePreview && (
          <Image
            src={filePreview}
            width={90}
            height={90}
            alt="Preview Uploaded Image"
            id="file-preview"
            className="w-full h-full object-cover"
          />
        )}
      </div>
    </div>
  );
};

export { ImageUpload };
