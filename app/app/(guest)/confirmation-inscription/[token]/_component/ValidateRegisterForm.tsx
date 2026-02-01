"use client";

import { registerValidation_action } from "@/actions/security/registerValidation-action";
import { Button } from "@/components/ui/button";
import { useToast } from "@/components/ui/use-toast";
import { HOME_PAGE } from "@/constants/routing";
import language from "@/messages/fr";
import { useRouter } from "next/navigation";
import { useState } from "react";

interface ValidateRegisterFormProps {
  token: string;
}

function ValidateRegisterForm({ token }: ValidateRegisterFormProps) {
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const router = useRouter();
  const handleAction = async () => {
    setIsLoading(true);
    const result = await registerValidation_action(token);

    if (result === 0) {
      toast({
        title:
          "Une erreur est survenue lors de la validation de votre inscription",
        variant: "destructive",
      });
      setIsLoading(false);
      return;
    }

    toast({
      title: "Votre inscription a été validée avec succès",
    });

    router.prefetch(HOME_PAGE);
  };
  return (
    <form action={handleAction}>
      <Button disabled={isLoading} type="submit">
        {language.registration.validation.submit}
      </Button>
    </form>
  );
}

export { ValidateRegisterForm };
