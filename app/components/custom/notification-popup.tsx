"use client";

import { useState, useTransition } from "react";
import { useRouter } from "next/navigation";
import { markNotificationRead_action } from "@/actions/notifications/markNotificationRead-action";
import { NOTIFICATIONS_PAGE } from "@/constants/routing";

export interface PopupNotificationData {
  uuid: string;
  title: string;
  body: string;
}

interface NotificationPopupProps {
  notification: PopupNotificationData;
}

const NotificationPopup = ({ notification }: NotificationPopupProps) => {
  const router = useRouter();
  const [isVisible, setIsVisible] = useState(true);
  const [, startTransition] = useTransition();

  const handleClose = () => {
    setIsVisible(false);
    startTransition(async () => {
      await markNotificationRead_action(notification.uuid);
      router.refresh();
    });
  };

  if (!isVisible) return null;

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center px-4">
      <div
        className="absolute inset-0 bg-black/70"
        onClick={handleClose}
      />
      <div className="relative z-10 w-full max-w-[440px] zone">
        <div className="flex items-center justify-between px-4 pt-4 pb-3 border-b border-white-6">
          <h2 className="text-medium font-bold text-primary">{notification.title}</h2>
          <button
            onClick={handleClose}
            className="ml-4 flex-shrink-0 text-gray hover:text-white transition-colors"
            aria-label="Fermer"
          >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        <div className="px-4 py-4 max-h-[60dvh] overflow-y-auto">
          <div
            className="notification-body text-sm text-gray leading-relaxed"
            dangerouslySetInnerHTML={{ __html: notification.body }}
          />
        </div>

        <div className="flex gap-3 px-4 pb-4 pt-3 border-t border-white-6">
          <a
            href={NOTIFICATIONS_PAGE}
            className="flex-1 text-center text-sm text-primary underline py-2"
            onClick={handleClose}
          >
            Voir toutes les notifications
          </a>
          <button
            onClick={handleClose}
            className="flex-1 bg-white text-black text-sm font-bold py-2 rounded-sm"
          >
            Fermer
          </button>
        </div>
      </div>
    </div>
  );
};

export { NotificationPopup };
