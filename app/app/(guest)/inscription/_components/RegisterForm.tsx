"use client";

import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { SubmitHandler, useForm } from "react-hook-form";
import { register_action } from "@/actions/security/register-action";
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
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import { Separator } from "@/components/custom/separator";
import { ImageUpload } from "@/components/custom/imageUpload";
import { useRouter } from "next/navigation";
import { LOGIN_PAGE } from "@/constants/routing";

export interface RegisterFormDataType {
  email: string;
  password: string;
  confirmPassword: string;
  pseudo: string;
  image?: undefined | any;
}

interface RegisterFormProps {
  userConfirmationByAdmin: number;
}

const RegisterFormSchema = z
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
      ),
    confirmPassword: z.string(),
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

const RegisterForm = ({userConfirmationByAdmin}: RegisterFormProps) => {
  const form = useForm<z.infer<typeof RegisterFormSchema>>({
    resolver: zodResolver(RegisterFormSchema),
    defaultValues: {
      email: "",
      password: "",
      confirmPassword: "",
      pseudo: "",
      image: undefined,
    },
    mode: "onChange",
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<RegisterFormDataType> = async (data) => {
    setIsLoading(true);

    // On génère le formData ici car on est obligé pour envoyer un fichier
    const form = new FormData();
    form.append("email", data.email);
    form.append("password", data.password);
    form.append("pseudo", data.pseudo);
    if(data.image[0]) {
      form.append("imageFile", data.image[0]);
    }

    const resString = await register_action(form);
    const res = JSON.parse(resString);

    const successMessage = userConfirmationByAdmin === 0 ? language.registration.message.success_send_email : language.registration.message.success_awaiting_moderation;

    if (res.status === 1) {
      toast({
        title: successMessage
      });
      router.push(LOGIN_PAGE);
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
      <form onSubmit={form.handleSubmit(onSubmit)} className="w-full">
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
          name="password"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.registration.form.field.password.label}
              </FormLabel>
              <FormControl>
                <Input
                  type="password"
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
                <Input
                  type="password"
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
        <Separator className="my-6" />
        <ImageUpload
          fieldName="image"
          form={form}
          description={language.registration.form.field.image.description}
        />
        <Button type="submit" disabled={isLoading}>
          {language.registration.form.submit.label}
        </Button>
      </form>
    </Form>
  );
};

export { RegisterForm };
