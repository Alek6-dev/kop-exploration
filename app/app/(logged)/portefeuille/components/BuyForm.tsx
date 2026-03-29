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
import { Button } from "@/components/ui/button";
import language from "@/messages/fr";
import { Separator } from "@/components/custom/separator";
import { useRouter } from "next/navigation";
import { CGU_PAGE, CGV_PAGE, WALLET_PAGE } from "@/constants/routing";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { cookies } from "next/headers";
import { packsArray } from "../page";
import Image from "next/image";
import { BuyPack_action } from "@/actions/wallet/buyPack-action";
import { Checkbox } from "@/components/ui/checkbox";
import { Link } from "lucide-react";
import { A } from "@/components/custom/link";

export interface BuyFormDataType {
    creditPackUuid: string,
    cgu: boolean,
    retractation: boolean,
}

interface BuyFormProps {
    packs: packsArray[]
}

const BuyFormSchema = z
  .object({
    creditPackUuid: z.string(),
    cgu: z.boolean().default(false),
    retractation: z.boolean().default(false),
})

const BuyForm = ({packs}: BuyFormProps) => {

  const form = useForm<z.infer<typeof BuyFormSchema>>({
    resolver: zodResolver(BuyFormSchema),
    defaultValues: {
    creditPackUuid: "",
    cgu: false,
    retractation: false,
    },
    mode: "onChange",
  });

  const [disabledSubmit, setDisabledSubmit] = useState(true);
  const [CGUChecked, setCGUChecked] = useState(false);
  const [RetractationChecked, setRetractationChecked] = useState(false);
  const [PackChecked, setPackChecked] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<BuyFormDataType> = async (data) => {
    setDisabledSubmit(true);

    //console.log("data in form : ", data);
    const resString: string = await BuyPack_action(data);
    const res = JSON.parse(resString);

    if (res.status === 1) {
        toast({
            title: res.message,
            variant: "info",
        });
        //console.log("res in form : ", res.paymentURL);
        router.push(res.paymentURL);
        return;
    }

    toast({
        title: res.message,
        variant: "destructive",
    });

    setDisabledSubmit(false);
  };

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="w-full">
        <FormField
            control={form.control}
            name="creditPackUuid"
            render={({ field }) => (
                <FormItem className="form-item custom-radio">
                <FormLabel>
                    <b>{language.wallet.form.field.pack.label}</b>
                </FormLabel>
                <FormControl>
                    <RadioGroup
                    onValueChange={(e) => {
                      field.onChange(e)
                      setPackChecked(true);
                    }}
                    defaultValue={field.value}
                    className="flex flex-col gap-0 mt-2"
                    >
                    {packs.map((option) => (
                        <FormItem className="custom-radio-item" key={option.uuid}>
                        <FormControl>
                            <RadioGroupItem value={option.uuid} />
                        </FormControl>
                        <FormLabel className="font-normal !h-12">
                            <div className="flex items-center w-full px-4">
                                <div className="relative w-[24px] h-[24px]">
                                    <Image
                                        src="/assets/icons/money/kop.svg"
                                        alt=""
                                        quality={100}
                                        width={24}
                                        height={24}
                                    />
                                </div>
                                <div className="pl-2">
                                    <b>{option.credit} {language.wallet.tokens}</b>
                                    <div className="text-sm font-normal">{option.message}</div>
                                </div>
                                <div className="ml-auto"><b>{option.price} €</b></div>
                            </div>
                        </FormLabel>
                        </FormItem>
                    ))}
                    </RadioGroup>
                </FormControl>
                <FormMessage />
                </FormItem>
            )}
        />

        <Separator className="my-4 separator-full" />

        <FormField
          control={form.control}
          name="cgu"
          render={({ field }) => (
            <FormItem className="flex flex-row items-start gap-x-2 mb-4">
              <FormControl>
                <Checkbox
                  checked={field.value}
                  onCheckedChange={(e) => {
                      field.onChange(e)
                      setCGUChecked(e === "indeterminate" ? false : e);
                  }}
                />
              </FormControl>
              <div className="mt-[2px] leading-none">
                <FormLabel>
                  {language.wallet.form.field.cgv.label}&nbsp;
                  <A href={CGV_PAGE}>
                    {language.wallet.form.field.cgv.label2}
                  </A>
                </FormLabel>
              </div>
            </FormItem>
          )}
        />

        <FormField
          control={form.control}
          name="retractation"
          render={({ field }) => (
            <FormItem className="flex flex-row items-start gap-x-2 mb-4">
              <FormControl>
                <Checkbox
                  checked={field.value}
                  onCheckedChange={(e) => {
                      field.onChange(e)
                      setRetractationChecked(e === "indeterminate" ? false : e);
                  }}
                />
              </FormControl>
              <div className="mt-[2px] leading-none">
                <FormLabel>
                  {language.wallet.form.field.retractation}
                </FormLabel>
              </div>
            </FormItem>
          )}
        />

        <Button type="submit" disabled={(CGUChecked === true && PackChecked === true && RetractationChecked === true) ? false : disabledSubmit}>
            {language.wallet.form.submit.label}
        </Button>
      </form>
    </Form>
  );
};

export { BuyForm };
