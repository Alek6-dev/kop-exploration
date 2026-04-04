"use client";

import { useState, useTransition } from "react";
import { useRouter } from "next/navigation";
import { markNotificationRead_action } from "@/actions/notifications/markNotificationRead-action";
import { deleteNotification_action } from "@/actions/notifications/deleteNotification-action";

export interface NotificationData {
  uuid: string;
  title: string;
  body: string;
  type: string;
  isRead: boolean;
  publishedAt: string | null;
}

interface NotificationItemProps {
  notification: NotificationData;
}

const NotificationItem = ({ notification }: NotificationItemProps) => {
  const router = useRouter();
  const [isExpanded, setIsExpanded] = useState(false);
  const [isRead, setIsRead] = useState(notification.isRead);
  const [isPending, startTransition] = useTransition();

  const handleExpand = () => {
    if (!isExpanded && !isRead) {
      startTransition(async () => {
        await markNotificationRead_action(notification.uuid);
        setIsRead(true);
        router.refresh();
      });
    }
    setIsExpanded(!isExpanded);
  };

  const handleDelete = (e: React.MouseEvent) => {
    e.stopPropagation();
    startTransition(async () => {
      await deleteNotification_action(notification.uuid);
      router.refresh();
    });
  };

  const formattedDate = notification.publishedAt
    ? new Date(notification.publishedAt).toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
      })
    : null;

  return (
    <div
      className="w-full zone block-animation mb-3"
      onClick={handleExpand}
      style={{ cursor: "pointer", opacity: isPending ? 0.6 : 1 }}
    >
      <div className="relative flex flex-col px-4 py-3">
        <div className="flex items-start justify-between gap-3">
          <div className="flex items-start gap-2 flex-1 min-w-0">
            {!isRead && (
              <span className="mt-1.5 flex-shrink-0 h-2 w-2 rounded-full bg-primary" />
            )}
            <div className="flex-1 min-w-0">
              <p className={`text-sm font-medium leading-snug ${!isRead ? "text-white" : "text-gray"}`}>
                {notification.title}
              </p>
              {formattedDate && (
                <p className="text-xs text-gray mt-0.5">{formattedDate}</p>
              )}
            </div>
          </div>
          <button
            onClick={handleDelete}
            className="flex-shrink-0 text-gray hover:text-white transition-colors"
            aria-label="Supprimer"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        {isExpanded && (
          <div
            className="notification-body mt-3 text-sm text-gray leading-relaxed"
            dangerouslySetInnerHTML={{ __html: notification.body }}
          />
        )}
      </div>
    </div>
  );
};

export { NotificationItem };
