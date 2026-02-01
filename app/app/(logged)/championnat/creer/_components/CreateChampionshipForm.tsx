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
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import { Separator } from "@/components/custom/separator";
import { createChampionship_action } from "@/actions/championship/createChampionship-action";
import { useRouter } from "next/navigation";
import { CHAMPIONSHIP_LISTING_PAGE } from "@/constants/routing";

const RACES_OPTIONS = ["4", "5", "6", "7", "8", "9", "10"];
const PLAYERS_OPTIONS = ["4", "6", "8", "10"];

export interface CreateChampionshipFormDataType {
  name: string;
  championshipNumberRace: string;
  championshipNumberPlayer: string;
  jokerEnabled: string;
  playerName: string;
}

const CreateChampionshipFormSchema = z.object({
  name: z
    .string()
    .min(3, { message: language.championship.create.form.field.name.error })
    .max(30, { message: language.championship.create.form.field.name.error }),
  championshipNumberRace: z.string(),
  championshipNumberPlayer: z.string(),
  jokerEnabled: z.string(),
  playerName: z
  .string()
  .min(3, { message: language.championship.join.form.field.team.error})
  .max(25, { message: language.championship.join.form.field.team.error }),
});

const CreateChampionshipForm = () => {
  const form = useForm<z.infer<typeof CreateChampionshipFormSchema>>({
    resolver: zodResolver(CreateChampionshipFormSchema),
    defaultValues: {
      name: "",
      championshipNumberRace: "4",
      championshipNumberPlayer: "4",
      jokerEnabled: "true",
      playerName: "",
    },
    mode: "onChange",
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<CreateChampionshipFormDataType> = async (
    data
  ) => {
    setIsLoading(true);

    const resString: string = await createChampionship_action(data);
    const res = JSON.parse(resString);

    if (res.status === 1) {
      toast({
        title: res.message,
      });
      router.push(CHAMPIONSHIP_LISTING_PAGE+"/"+res.uuid);
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
          name="name"
          render={({ field }) => (
            <FormItem className="form-item">
              <FormLabel>
                {language.championship.create.form.field.name.label}
              </FormLabel>
              <FormControl>
                <Input
                  placeholder={
                    language.championship.create.form.field.name.placeholder
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
          name="championshipNumberRace"
          render={({ field }) => (
            <FormItem className="form-item custom-radio">
              <FormLabel>
                {language.championship.create.form.field.races.label}
              </FormLabel>
              <FormControl>
                <RadioGroup
                  onValueChange={field.onChange}
                  defaultValue={field.value}
                  className="flex gap-2"
                >
                  {RACES_OPTIONS.map((option) => (
                    <FormItem className="custom-radio-item" key={option}>
                      <FormControl>
                        <RadioGroupItem value={option} />
                      </FormControl>
                      <FormLabel className="font-normal">{option}</FormLabel>
                    </FormItem>
                  ))}
                </RadioGroup>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        <FormField
          control={form.control}
          name="championshipNumberPlayer"
          render={({ field }) => (
            <FormItem className="form-item custom-radio">
              <FormLabel>
                {language.championship.create.form.field.players.label}
              </FormLabel>
              <FormControl>
                <RadioGroup
                  onValueChange={field.onChange}
                  defaultValue={field.value}
                  className="flex gap-2"
                >
                  {PLAYERS_OPTIONS.map((option) => (
                    <FormItem className="custom-radio-item" key={option}>
                      <FormControl>
                        <RadioGroupItem value={option} />
                      </FormControl>
                      <FormLabel className="font-normal">{option}</FormLabel>
                    </FormItem>
                  ))}
                </RadioGroup>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        {/* <FormField
          control={form.control}
          name="jokerEnabled"
          render={({ field }) => (
            <FormItem className="form-item custom-radio">
              <FormLabel>
                {language.championship.create.form.field.joker.label}
              </FormLabel>
              <FormControl>
                <RadioGroup
                  onValueChange={field.onChange}
                  defaultValue={field.value}
                  className="flex gap-2"
                >
                  <FormItem className="custom-radio-item">
                    <FormControl>
                      <RadioGroupItem value="true" />
                    </FormControl>
                    <FormLabel className="font-normal">Oui</FormLabel>
                  </FormItem>
                  <FormItem className="custom-radio-item">
                    <FormControl>
                      <RadioGroupItem value="" />
                    </FormControl>
                    <FormLabel className="font-normal">Non</FormLabel>
                  </FormItem>
                </RadioGroup>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        /> */}

        <Separator className="mt-4 mb-6" />

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

        <Separator className="my-6" />
        <Button type="submit" disabled={isLoading}>
          {language.championship.create.form.submit.label}
        </Button>
      </form>
    </Form>
  );
};

export { CreateChampionshipForm };
