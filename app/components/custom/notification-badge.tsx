interface NotificationBadgeProps {
  hasUnread: boolean;
  children: React.ReactNode;
}

const NotificationBadge = ({ hasUnread, children }: NotificationBadgeProps) => {
  return (
    <span className="relative inline-block">
      {children}
      {hasUnread && (
        <span className="absolute -top-[5px] -right-[5px] block h-[10px] w-[10px] rounded-full bg-red z-50" />
      )}
    </span>
  );
};

export { NotificationBadge };
