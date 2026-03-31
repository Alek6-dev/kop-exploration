"use client";

import { MenuItem } from "@/components/custom/menuItem";
import IconTrophy from "@/public/assets/icons/menu/trophy.svg";
import IconQuiz from "@/public/assets/icons/menu/quiz.svg";
import IconPaddock from "@/public/assets/icons/menu/paddock.svg";
import IconStore from "@/public/assets/icons/menu/store.svg";
import IconHub from "@/public/assets/icons/menu/hub.svg";
import language from "@/messages/fr";
import { CHAMPIONSHIP_LISTING_PAGE, HOME_PAGE, MY_PADDOCK_PAGE, QUIZ_PAGE, SHOP_CARS_PAGE } from "@/constants/routing";

const Menu = () => {
  return (
    <nav className="fixed bottom-0 left-1/2 -translate-x-1/2 bg-black border-t border-[#3D3E40] flex w-full max-w-[480px] h-14 z-10">
      <div className="flex justify-end flex-1 h-full">
        <MenuItem title={language.hub.championship} href={CHAMPIONSHIP_LISTING_PAGE}>
          <IconTrophy />
        </MenuItem>
        <MenuItem title={language.hub.quiz} href={QUIZ_PAGE} className="menu-item-quiz">
          <IconQuiz />
        </MenuItem>
      </div>
      <a
        href={HOME_PAGE}
        className="flex-initial flex-shrink-0 h-full pl-3 pr-2 flex-centering"
      >
        <div className="flex-centering rounded-md gradient-white-gray w-[46px] h-[46px]">
          <IconHub />
        </div>
      </a>
      <div className="flex flex-1 h-full">
        <MenuItem
          title={language.hub.paddock_menu}
          href={MY_PADDOCK_PAGE}
          className="paddock"
        >
          <IconPaddock />
        </MenuItem>
        <MenuItem title={language.hub.store} href={SHOP_CARS_PAGE}>
          <IconStore />
        </MenuItem>
      </div>
    </nav>
  );
};

export { Menu };
