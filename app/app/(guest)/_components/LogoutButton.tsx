"use client";

import logOut_action from "@/actions/security/logOut-action";
import language from "@/messages/fr";

const LogoutButton = () => {
  const handleClick = async () => {
    await logOut_action();
  };

  return (
    <div onClick={handleClick} className="p-4 text-medium font-bold">
      {language.profile.myprofile.logout}
    </div>
  );
};

export { LogoutButton };
