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
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import { Separator } from "@/components/custom/separator";
import { useRouter } from "next/navigation";
import { joinChampionship_action } from "@/actions/championship/joinChampionship-action";

export interface JoinChampionshipFormDataType {
  playerName: string;
  code: string;
}

const JoinChampionshipFormSchema = z.object({
  playerName: z
    .string()
    .min(3, { message: language.championship.join.form.field.team.error })
    .max(25, { message: language.championship.join.form.field.team.error }),
  code: z.string(),
});

const JoinChampionshipForm = () => {
  const form = useForm<z.infer<typeof JoinChampionshipFormSchema>>({
    resolver: zodResolver(JoinChampionshipFormSchema),
    defaultValues: {
      playerName: "",
      code: ""
    },
    mode: "onChange",
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<JoinChampionshipFormDataType> = async (
    data
  ) => {
    setIsLoading(true);

    //console.log(data);

    const resString: string = await joinChampionship_action(data);
    const res = JSON.parse(resString);

    if (res.status === 1) {
      toast({
        title: res.message,
      });
      router.push('/championnat/'+res.uuid);
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
          name="code"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.championship.join.form.field.code.label}
              </FormLabel>
              <FormControl>
                <Input
                  placeholder={
                    language.championship.join.form.field.code.placeholder
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
          name="playerName"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.championship.join.form.field.team.label}
              </FormLabel>
              <FormControl>
                <Input
                  placeholder={
                    language.championship.join.form.field.team.placeholder
                  }
                  {...field}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        <p className="-mt-2 mb-6 text-sm text-gray"><i>{language.championship.join.warning}</i></p>

        <Separator className="mt-4 mb-6" />
        <Button type="submit" disabled={isLoading}>
          {language.championship.join.form.submit.label}
        </Button>
      </form>
    </Form>
  );
};

export { JoinChampionshipForm };
