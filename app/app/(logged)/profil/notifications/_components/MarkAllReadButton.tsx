"use client";

import { useTransition } from "react";
import { useRouter } from "next/navigation";
import { markAllNotificationsRead_action } from "@/actions/notifications/markAllNotificationsRead-action";

const MarkAllReadButton = () => {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();

  const handleClick = () => {
    startTransition(async () => {
      await markAllNotificationsRead_action();
      router.refresh();
    });
  };

  return (
    <button
      onClick={handleClick}
      disabled={isPending}
      className="text-sm text-primary underline block-animation"
    >
      {isPending ? "..." : "Tout marquer comme lu"}
    </button>
  );
};

export { MarkAllReadButton };
