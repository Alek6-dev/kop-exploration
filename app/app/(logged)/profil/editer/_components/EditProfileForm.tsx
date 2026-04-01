"use client";

import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { SubmitHandler, useForm } from "react-hook-form";
import { useToast } from "@/components/ui/use-toast";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { PasswordInput } from "@/components/custom/password-input";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import { Separator } from "@/components/custom/separator";
import { useRouter } from "next/navigation";
import { useSession } from "@/hooks/security/useSession";
import { editProfile_action } from "@/actions/profile/editProfile-action";
import { ImageUpload } from "@/components/custom/imageUpload";

export interface EditProfileFormDataType {
  email: string;
  password?: string | number | readonly string[] | undefined;
  confirmPassword?: string | number | readonly string[] |undefined;
  pseudo: string;
  //image: Blob[];
  image?: undefined | any;
}

const accepted_image_types = [
  "image/jpeg",
  "image/jpg",
  "image/png",
  "image/webp",
];

const EditProfileFormSchema = z
  .object({
    email: z
      .string()
      .email({ message: language.form.error.not_email })
      .min(1, { message: language.form.error.required_field }),
    password: z
      .string()
      .refine(
        (val) =>
          /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{10,}$/.test(
            val
          ),
        { message: language.form.error.password_min_length }
      )
      .optional()
      .or(z.literal('')),
    confirmPassword: z
      .string()
      .optional()
      .or(z.literal('')),
    pseudo: z.string().min(1, { message: language.form.error.required_field }),
    image: z.any(),
    //.refine((file) => file?.size <= 30000000, `Max image size is 5MB.`)
    //.refine((file) => file?.length > 0, language.form.error.required_file)
    //.refine((files) => files[0]?.size <= 3000000, language.form.error.file_size)
    //.refine((files) => accepted_image_types.includes(files[0]?.type), language.form.error.non_authorized_image_type),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: language.form.error.non_matching_password,
    path: ["confirmPassword"], // path of error
  });

const EditProfileForm = (sessionValues?: any) => {
  const session = useSession();
  const [displayPassword, setDisplayPassword] = useState(false);
  let initialValues;

  if(session) {
    initialValues = {
      email: session.email,
      password: "",
      confirmPassword: "",
      pseudo: session.pseudo,
      image: null
    }
  }

  //console.log(session?.avatar_url);

  const form = useForm<z.infer<typeof EditProfileFormSchema>>({
    resolver: zodResolver(EditProfileFormSchema),
    defaultValues: initialValues,
    mode: "onChange",
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<EditProfileFormDataType> = async (data) => {
    setIsLoading(true);
    //console.log("submit handler");

    const imageForm = new FormData();
    let imageToSend: any = null;
    if(data.image != null) {
      imageForm.append("imageFile", data.image[0]);
      imageToSend = imageForm;
    }

    //console.log("image : ", imageToSend);

    const dataToSend = [ data.email, data.pseudo, data.password, imageToSend];

    //console.log("dataToSend : ", dataToSend);

    const resString = await editProfile_action(dataToSend);

    const res = JSON.parse(resString);

    if (res.status === 1) {
      toast({
        title: res.message
      });
      setIsLoading(false);
      return;
    }

    toast({
      title: res.message,
      variant: "destructive",
    });

    setIsLoading(false);
  };

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="w-full flex flex-col">
        <FormField
          control={form.control}
          name="email"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.registration.form.field.email.label}
              </FormLabel>
              <FormControl>
                <Input
                  placeholder={
                    language.registration.form.field.email.placeholder
                  }
                  {...field}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <FormField
          control={form.control}
          name="pseudo"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.registration.form.field.pseudo.label}
              </FormLabel>
              <FormControl>
                <Input
                  placeholder={
                    language.registration.form.field.pseudo.placeholder
                  }
                  {...field}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Separator className="mt-2 mb-6" />
        <ImageUpload
          fieldName="image"
          form={form}
          description={language.registration.form.field.image.description}
          defaultPreview={session?.avatar_url && `${process.env.NEXT_PUBLIC_API_URL + '/' +  session?.avatar_url}`}
        />

        <Separator className="mb-6" />
        <p className="mb-2"><b>Modifier votre de mot de passe</b></p>
        <p className={"mt-2 mb-4" + (displayPassword ? " hidden" : "")}>Si vous souhaitez modifier votre mot de passe, <span className="link" onClick={() => setDisplayPassword(true)}>cliquez ici</span>.</p>

        <div id="js-new-password" className={"mt-2" + (displayPassword  ? "" : " hidden")}>
          <FormField
            control={form.control}
            name="password"
            render={({ field }) => (
              <FormItem className="form-item">
                <FormLabel>
                  {language.registration.form.field.password.label}
                </FormLabel>
                <FormControl>
                  <PasswordInput
                    placeholder={
                      language.registration.form.field.password.placeholder
                    }
                    {...field}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />
          <FormField
            control={form.control}
            name="confirmPassword"
            render={({ field }) => (
              <FormItem className="form-item">
                <FormLabel>
                  {language.registration.form.field.password_confirm.label}
                </FormLabel>
                <FormControl>
                  <PasswordInput
                    placeholder={
                      language.registration.form.field.password_confirm
                        .placeholder
                    }
                    {...field}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />
        </div>

        <Separator className="mb-6 mt-2" />

        <Button type="submit" disabled={isLoading}>
          {language.profile.edit.form.submit.label}
        </Button>

      </form>
    </Form>
  );
};

export { EditProfileForm };
