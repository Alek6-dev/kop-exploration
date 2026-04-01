"use client";

import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { SubmitHandler, useForm } from "react-hook-form";

import { logIn_action } from "@/actions/security/logIn-action";
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
import { PasswordInput } from "@/components/custom/password-input";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import Link from "next/link";
import { A } from "@/components/custom/link";
import { FORGOT_PASSWORD_PAGE } from "@/constants/routing";

export interface LoginFormDataType {
  email: string;
  password: string;
}

const LoginFormSchema = z.object({
  email: z
    .string()
    .email({ message: language.form.error.not_email })
    .min(1, { message: language.form.error.required_field }),
  password: z.string().min(1, { message: language.form.error.required_field }),
});

const LoginForm = () => {
  const form = useForm<z.infer<typeof LoginFormSchema>>({
    resolver: zodResolver(LoginFormSchema),
    defaultValues: {
      email: "",
      password: "",
    },
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  const onSubmit: SubmitHandler<LoginFormDataType> = async (data) => {
    setIsLoading(true);

    const resString = await logIn_action(data);
    const res = JSON.parse(resString);

    if (res.status === 1) return;

    toast({
      title: res.message,
      variant: "destructive",
    });

    setIsLoading(false);
  };

  return (
    <Form {...form}>
      <form
        onSubmit={form.handleSubmit(onSubmit)}
        className="flex flex-col w-full"
      >
        <FormField
          control={form.control}
          name="email"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>{language.login.form.field.email.label}</FormLabel>
              <FormControl>
                <Input
                  placeholder={language.login.form.field.email.placeholder}
                  {...field}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        <A
          href={FORGOT_PASSWORD_PAGE}
          className="text-sm text-gray font-normal ml-auto -mb-[18px] z-10"
        >
          {language.login.forgottenPassword}
        </A>
        <FormField
          control={form.control}
          name="password"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>{language.login.form.field.password.label}</FormLabel>
              <FormControl>
                <PasswordInput
                  placeholder={language.login.form.field.password.placeholder}
                  {...field}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Button type="submit" disabled={isLoading}>
          {language.login.form.submit.label}
        </Button>
      </form>
    </Form>
  );
};

export { LoginForm };
