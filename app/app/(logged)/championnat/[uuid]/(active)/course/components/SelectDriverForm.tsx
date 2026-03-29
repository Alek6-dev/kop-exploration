"use client";

import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
  } from "@/components/ui/form";
import { z } from "zod";
import { SubmitHandler, useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import language from "@/messages/fr";
import { DriverRadioButton } from "./DriverRadioButton";
import { ChampionshipActivePlayerArray } from "@/type/championship";
import { SelectedStrategyArray } from "./StrategyForm";


export interface SelectDriverFormProps {
  playerData: ChampionshipActivePlayerArray,
  championshipDataUuid: string,
  raceStatus: number,
  type: 'gp' | 'duel',
  selectedStrategy: SelectedStrategyArray,
  handleStrategy: any,
}

export interface SelectDriverFormDataType {
  driverUuid: string,
}

const SelectDriverFormSchema = z.object({
  driverUuid: z.string(),
});

const SelectDriverForm = ({ playerData, championshipDataUuid, raceStatus, type, selectedStrategy, handleStrategy }: SelectDriverFormProps) => {
  let defaultValueDriver: string;

  if(type === "gp") {
    defaultValueDriver = selectedStrategy.driverGP;
    //console.log("defaultValueDriverGP :", defaultValueDriver);
  } else {
    defaultValueDriver = selectedStrategy.driverDuel;
    //console.log("defaultValueDriverDuel :", defaultValueDriver);
  }

  const form = useForm<z.infer<typeof SelectDriverFormSchema>>({
    resolver: zodResolver(SelectDriverFormSchema),
    defaultValues: {
      driverUuid: defaultValueDriver,
    },
    mode: "onChange",
  });

  const onSubmit: SubmitHandler<SelectDriverFormDataType> = async (
    data
  ) => {
    if(type === "gp"){
      //console.log("driver submitted for GP :", data);
      handleStrategy({...selectedStrategy, driverGP: data.driverUuid});
    }
    else {
      //console.log("driver submitted for duel :", data);
      handleStrategy({...selectedStrategy, driverDuel: data.driverUuid});
    }
  };

  //console.log("player data : ", playerData);

  const driver1 = {
    name: playerData.selectedDriver1.name,
    team: playerData.selectedDriver1.team.name,
    uuid: playerData.selectedDriver1.uuid,
    image: playerData.selectedDriver1.image,
    raceUsage: playerData.remainingUsageDriver1,
    duelUsage: playerData.remainingDuelUsageDriver1,
    driversMaxUsage: playerData.maxRemainingUsageDriver,
  }

  const driver2 = {
    name: playerData.selectedDriver2.name,
    team: playerData.selectedDriver2.team.name,
    uuid: playerData.selectedDriver2.uuid,
    image: playerData.selectedDriver2.image,
    raceUsage: playerData.remainingUsageDriver2,
    duelUsage: playerData.remainingDuelUsageDriver2,
    driversMaxUsage: playerData.maxRemainingUsageDriver,
  }

  return(
    <Form {...form}>
        <form onChange={form.handleSubmit(onSubmit)} className="w-full">
            <FormField
                control={form.control}
                name="driverUuid"
                render={({ field }) => (
                <FormItem className="custom-radio px-4 mt-5">
                    <FormLabel className="hidden">
                    {language.championship.create.form.field.joker.label}
                    </FormLabel>
                    <FormControl>
                    <RadioGroup
                        onValueChange={field.onChange}
                        defaultValue={field.value}
                        className="grid grid-cols-2 gap-4"
                        disabled={raceStatus !== 2}
                    >
                        <DriverRadioButton data={driver1} type={type} defaultValue={field.value} />
                        <DriverRadioButton data={driver2} type={type} defaultValue={field.value} />
                    </RadioGroup>
                    </FormControl>
                    <FormMessage />
                </FormItem>
                )}
            />
        </form>
    </Form>
  )
}

export { SelectDriverForm };
