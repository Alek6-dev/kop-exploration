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
import { useRouter } from "next/navigation";
import { Block } from "@/components/custom/block";
import { ShopGrid } from "../../boutique/components/ShopGrid";
import { shopItemArray } from "../../boutique/page";
import IconCheck from "@/public/assets/icons/small/check.svg";
import { Container } from "@/components/custom/container";

const shopData: Array<shopItemArray> = [
    {
        uuid: '31ecb2fc-9808-497c-b16d-07e9370ec19a',
        name: 'Brody Prohaska',
        description: 'Quia rerum nihil enim aut consequatur odio non. Aut eos impedit laborum odit qui. Ducimus dolore voluptatem dignissimos aliquid ut qui quidem. Beatae consectetur voluptatibus voluptas itaque.',
        price: 69,
        type: 3,
        color: '#ea9557',
        image1: 'cosmetic-1-374eefcf-ad14-4f7f-972c-0410a24e2880-6634f6fa3c636248975186.png',
        image2: 'cosmetic-2-8628c646-33d2-4c83-aede-bf5ad6542c49-6634f6fa3c78f692680115.png',
        isSelected: false,
        isPossessed: true,
    },
    {
        uuid: 'a395ac3e-110e-4564-a979-2eb23bf7465f',
        name: 'Prof. Deangelo Fritsch',
        description: 'Reiciendis non et excepturi sit suscipit. Quo illo maxime harum quae autem. Temporibus sequi omnis sed culpa. Tenetur at optio quisquam id quam quam nihil.',
        price: 296,
        type: 2,
        color: '#11c294',
        image1: 'cosmetic-1-3d20d02a-5159-4370-be2e-eed89b86cff4-6634f6fa3cb18274042687.png',
        image2: 'cosmetic-2-5a075440-ff3c-45e8-a052-e673c98fec3c-6634f6fa3cbde517756905.png',
        isSelected: false,
        isPossessed: true,
    },
    {
        uuid: '83afe54c-2b2a-4c6d-a000-d10df9a38162',
        name: 'Monroe Cummings Jr.',
        description: 'Quidem consequatur quod cumque exercitationem. Maiores voluptas necessitatibus aut neque ratione sapiente qui saepe. Autem et harum reprehenderit quibusdam saepe quae.',
        price: 85,
        type: 2,
        color: '#41624e',
        image1: 'cosmetic-1-59ab8f67-2721-4dbf-bc9f-a9afc8822f44-6634f6fa3cefc895777078.png',
        image2: 'cosmetic-2-6a24d358-687a-4add-aa91-f9e8564970d4-6634f6fa3d06b006987618.png',
        isSelected: false,
        isPossessed: true,
    }
];


export interface PromoCodeFormDataType {
  code: string;
}

const PromoCodeFormSchema = z.object({
  code: z.string(),
});

const PromoCodeForm = () => {
  const form = useForm<z.infer<typeof PromoCodeFormSchema>>({
    resolver: zodResolver(PromoCodeFormSchema),
    defaultValues: {
      code: ""
    },
    mode: "onChange",
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();

  const onSubmit: SubmitHandler<PromoCodeFormDataType> = async (
    data
  ) => {
    setIsLoading(true);

    //console.log(data);

    // const resString: string = await applyPromoCode_action(data);
    // const res = JSON.parse(resString);

    // if (res.status === 1) {
    //   toast({
    //     title: res.message,
    //   });
    //   router.push('/championnat/'+res.uuid);
    //   return;
    // }

    // toast({
    //   title: res.message,
    //   variant: "destructive",
    // });

    // setIsLoading(false);
  };

  return (
    <>
        <Container className="mt-6">
            <Block containerClassName="block-animation mb-4" childClassName="p-4">
                <p className="mb-4 text-gray">{language.promocode.description}</p>

                <Form {...form}>
                    <form onSubmit={form.handleSubmit(onSubmit)} className="w-full">
                        <FormField
                        control={form.control}
                        name="code"
                        render={({ field }) => (
                            <FormItem className="form-item">
                            <FormLabel>
                                {language.promocode.form.field.label}
                            </FormLabel>
                            <FormControl>
                                <Input
                                placeholder={
                                    language.promocode.form.field.placeholder
                                }
                                {...field}
                                />
                            </FormControl>
                            <FormMessage />
                            </FormItem>
                        )}
                        />

                        <Button type="submit" disabled={isLoading}>
                        {language.promocode.form.submit}
                        </Button>
                    </form>
                </Form>
            </Block>
        </Container>

        <Container className="mt-6">
            <div className="block-animation  bg-green text-white h4 p-4 rounded-lg flex">
                <div>
                    <h3 className="h3">{language.promocode.congrats}</h3>
                    <p className="mt-2 w-full">{language.promocode.earnings}</p>
                </div>
                <div className="rounded-full w-8 h-8 shrink-0 bg-white svg-green ml-2 flex-centering">
                    <IconCheck />
                </div>
            </div>
        </Container>

        <ShopGrid shopData={shopData} credits={0} />
    </>
  );
};

export { PromoCodeForm };
