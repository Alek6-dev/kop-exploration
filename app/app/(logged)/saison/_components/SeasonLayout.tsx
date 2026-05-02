"use client";

import React from "react";
import { usePathname } from "next/navigation";
import Link from "next/link";
import { Container } from "@/components/custom/container";
import { SeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import {
  SEASON_GAME_PAGE,
  SEASON_GAME_RANKING_PAGE,
  SEASON_GAME_MY_TEAM_PAGE,
  SEASON_GAME_PALMARES_PAGE,
} from "@/constants/routing";

const tabs = [
  { label: "Stratégie", href: SEASON_GAME_PAGE },
  { label: "Classement", href: SEASON_GAME_RANKING_PAGE },
  { label: "Mon équipe", href: SEASON_GAME_MY_TEAM_PAGE },
  { label: "Palmarès", href: SEASON_GAME_PALMARES_PAGE },
];

interface SeasonLayoutProps {
  participation: SeasonParticipation;
  children: React.ReactNode;
}

const SeasonLayout = ({ participation, children }: SeasonLayoutProps) => {
  const pathname = usePathname();

  return (
    <div>
      <div className="bg-[#1C1D1F] border-b border-white/10 sticky top-[56px] z-10">
        <Container>
          <div className="flex items-center justify-between py-2">
            <span className="text-xs text-gray">{participation.seasonName}</span>
            <span className="text-xs font-bold text-primary">{participation.walletBalance} ¤</span>
          </div>
          <div className="flex gap-1 pb-2 overflow-x-auto no-scrollbar">
            {tabs.map((tab) => {
              const isActive = pathname === tab.href || (tab.href === SEASON_GAME_PAGE && pathname.startsWith("/saison/strategie"));
              return (
                <Link
                  key={tab.href}
                  href={tab.href}
                  className={`flex-shrink-0 px-3 py-1 rounded-full text-sm font-medium transition-colors ${
                    isActive
                      ? "bg-primary text-black"
                      : "text-gray hover:text-white"
                  }`}
                >
                  {tab.label}
                </Link>
              );
            })}
          </div>
        </Container>
      </div>
      <Container className="py-4">{children}</Container>
    </div>
  );
};

export { SeasonLayout };
