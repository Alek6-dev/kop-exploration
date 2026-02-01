"use client";

import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { SubmitHandler, useForm } from "react-hook-form";

import { motdepasseoublie_action } from "@/actions/security/motdepasseoublie-action";
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

export interface MotDePasseOublieFormDataType {
  email: string;
}

const MotDePasseOublieFormSchema = z.object({
  email: z
    .string()
    .email({ message: language.form.error.not_email })
    .min(1, { message: language.form.error.required_field }),
});

const MotDePasseOublieForm = () => {
  const form = useForm<z.infer<typeof MotDePasseOublieFormSchema>>({
    resolver: zodResolver(MotDePasseOublieFormSchema),
    defaultValues: {
      email: "",
    },
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  const onSubmit: SubmitHandler<MotDePasseOublieFormDataType> = async (data) => {
    setIsLoading(true);
    const resString = await motdepasseoublie_action(data);
    const res = JSON.parse(resString);

    if (res.status === 1) {
      toast({
        title:
          "Vous allez recevoir un email contenant un lien permettant de réinitialiser votre mot de passe.",
      });
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
          name="email"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>{language.login.form.field.email.label}</FormLabel>
              <FormControl>
                <Input placeholder={language.forgot_password_request.form.field.email.placeholder} {...field} />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Button type="submit" disabled={isLoading}>{language.forgot_password_request.form.submit.label}</Button>
      </form>
    </Form>
  );
};

export { MotDePasseOublieForm };
