"use client";

import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { SubmitHandler, useForm } from "react-hook-form";

import { reinitialisermotdepasse_action } from "@/actions/security/reinitialisermotdepasse-action";
import { useToast } from "@/components/ui/use-toast";
import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import router, { useRouter } from "next/navigation";
import { HOME_PAGE } from "@/constants/routing";

export interface ReinitialiserMotDePasseFormDataType {
  password: string;
  confirmPassword: string;
}

const ReinitialiserMotDePasseFormSchema = z.object({
  password: z
    .string()
    .min(10, { message: language.form.error.password_min_length }),
  confirmPassword: z
    .string()
    .min(10, { message: language.form.error.password_min_length }),
});

const ReinitialiserMotDePasseForm = ({ token }: { token: string }) => {
  const form = useForm<z.infer<typeof ReinitialiserMotDePasseFormSchema>>({
    resolver: zodResolver(ReinitialiserMotDePasseFormSchema),
    defaultValues: {
      password: "",
      confirmPassword: "",
    },
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<ReinitialiserMotDePasseFormDataType> = async (data) => {
    setIsLoading(true);
    const resString = await reinitialisermotdepasse_action(data, token);
    const res = JSON.parse(resString);

    if (res.status === 1) {
      toast({
        title:
          "Parfait. Votre nouveau mot de passe est correctement enregistré et vous êtes connecté à votre compte.",
      });
      router.push(HOME_PAGE);
      return;
    };

    toast({
      title: res.message,
      variant: "destructive",
    });

    setIsLoading(false);
  };

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="flex flex-col w-full">
        <FormField
          control={form.control}
          name="password"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>{language.forgot_password_action.form.field.password.first_options.label}</FormLabel>
              <FormControl>
                <Input type="password" placeholder={language.forgot_password_action.form.field.password.first_options.placeholder} {...field} />
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
              <FormLabel>{language.forgot_password_action.form.field.password.second_options.label}</FormLabel>
              <FormControl>
                <Input type="password" placeholder={language.forgot_password_action.form.field.password.second_options.placeholder} {...field} />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Button type="submit" disabled={isLoading}>{language.forgot_password_action.form.submit.label}</Button>
      </form>
    </Form>
  );
};

export { ReinitialiserMotDePasseForm };
