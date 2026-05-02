import Image from "next/image";
import { redirect } from "next/navigation";
import { getSession } from "@/lib/security";
import { getSeasonParticipation } from "@/actions/season-game/getSeasonParticipation-action";
import { SeasonRosterDriver, SeasonRosterTeam } from "@/actions/season-game/getSeasonParticipation-action";
import { LOGIN_PAGE, SEASON_GAME_PAGE, SEASON_GAME_COMPOSITION_PAGE } from "@/constants/routing";
import { SeasonLayout } from "../_components/SeasonLayout";

const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? "";

const IconM = () => (
  <Image src="/assets/icons/money/m.svg" alt="M" quality={100} width={14} height={14} className="inline-block ml-1 -translate-y-[1px]" />
);


const UsageBar = ({ usagesLeft, maxUsages }: { usagesLeft: number; maxUsages: number }) => (
  <div className="flex items-center gap-2">
    <div className="usage-bar h-[4px] overflow-hidden rounded-full flex" style={{ width: maxUsages * 8 - 2 + "px" }}>
      {[...Array(maxUsages)].map((_, i) => (
        <div
          key={i}
          className={"w-[6px] shrink-0 h-full -skew-x-[30deg] mr-[2px] " + (i < usagesLeft ? "bg-primary" : "bg-[#444]")}
        />
      ))}
    </div>
    <span className="text-xs font-semibold tabular-nums">
      <span className="text-primary">{usagesLeft}</span>
      <span className="text-[#666]">/{maxUsages}</span>
    </span>
  </div>
);

const DriverCard = ({ d }: { d: SeasonRosterDriver }) => (
  <div className="zone overflow-hidden">
    <div className="flex flex-row items-stretch h-[84px]">
      <div
        className="gradient-avatar-driver flex-shrink-0 w-[72px] flex items-end justify-center overflow-hidden"
        style={{ backgroundColor: d.teamColor ?? "rgba(255,255,255,0.06)" }}
      >
        <Image
          src={d.driverImage ? `${apiUrl}/${d.driverImage}` : "/assets/images/driver/driver-generic.svg"}
          alt={d.driverName}
          width={72}
          height={84}
          quality={100}
          className="block h-full w-full object-contain object-bottom"
        />
      </div>

      <div className="flex-1 min-w-0 flex flex-col justify-center px-3 gap-0.5">
        <p className="font-bold uppercase text-sm leading-tight truncate">{d.driverName}</p>
        <p className="text-xs text-gray truncate">{d.teamName ?? "—"}</p>
        <div className="mt-1.5">
          <UsageBar usagesLeft={d.usagesLeft} maxUsages={d.maxUsages} />
        </div>
      </div>

      <div
        className="flex-shrink-0 flex items-center justify-center px-4"
        style={{ borderLeft: "1px solid rgba(255,255,255,0.06)" }}
      >
        <span className="text-lg font-bold tabular-nums leading-none">{d.purchasePrice}<IconM /></span>
      </div>
    </div>
  </div>
);

const TeamCard = ({ t }: { t: SeasonRosterTeam }) => (
  <div className="zone overflow-hidden">
    <div className="flex flex-row items-stretch h-[84px]">
      <div
        className="gradient-avatar-team flex-shrink-0 w-[72px] flex items-center justify-center overflow-hidden p-2"
        style={{ backgroundColor: t.teamColor ?? "rgba(255,255,255,0.06)" }}
      >
        <Image
          src={t.teamImage ? `${apiUrl}/${t.teamImage}` : "/assets/images/team/team-generic.svg"}
          alt={t.teamName}
          width={56}
          height={56}
          quality={100}
          className="block h-full w-full object-contain"
        />
      </div>

      <div className="flex-1 min-w-0 flex flex-col justify-center px-3 gap-0.5">
        <p className="font-bold uppercase text-sm leading-tight truncate">{t.teamName}</p>
        <p className="text-xs text-gray">Écurie</p>
        <div className="mt-1.5">
          <UsageBar usagesLeft={t.usagesLeft} maxUsages={t.maxUsages} />
        </div>
      </div>

      <div
        className="flex-shrink-0 flex items-center justify-center px-4"
        style={{ borderLeft: "1px solid rgba(255,255,255,0.06)" }}
      >
        <span className="text-lg font-bold tabular-nums leading-none">{t.purchasePrice}<IconM /></span>
      </div>
    </div>
  </div>
);

export default async function SeasonMonEquipePage() {
  const session = await getSession();
  if (!session) redirect(LOGIN_PAGE);

  const participation = await getSeasonParticipation();
  if (!participation) redirect(SEASON_GAME_PAGE);
  if (!participation.hasRoster || !participation.roster) redirect(SEASON_GAME_COMPOSITION_PAGE);

  const { roster } = participation;

  const rewardsTotal = Math.max(0, participation.walletBalance + roster.budgetSpent - 500);

  return (
    <SeasonLayout participation={participation}>
      <div className="space-y-3">

        <div className="space-y-2 pb-2">
          <p className="text-[10px] text-gray uppercase tracking-wider px-1">Budget</p>
          <div className="zone p-4">
            <div className="flex items-start">
              <div className="flex-1 flex flex-col gap-2">
                <p className="text-[10px] text-gray uppercase tracking-wider">Budget dépensé</p>
                <p className="text-xl font-bold leading-none tabular-nums">{roster.budgetSpent}<IconM /></p>
              </div>
              <div
                className="flex-1 flex flex-col items-center gap-2 px-3"
                style={{ borderLeft: "1px solid rgba(255,255,255,0.08)", borderRight: "1px solid rgba(255,255,255,0.08)" }}
              >
                <p className="text-[10px] text-gray uppercase tracking-wider">Solde actuel</p>
                <p className="text-xl font-bold leading-none text-primary tabular-nums">{participation.walletBalance}<IconM /></p>
              </div>
              <div className="flex-1 flex flex-col items-end gap-2">
                <p className="text-[10px] text-gray uppercase tracking-wider">Récompenses</p>
                <p className="text-xl font-bold leading-none">{rewardsTotal}<IconM /></p>
              </div>
            </div>
          </div>
        </div>

        <div className="space-y-2 pb-2">
          <p className="text-[10px] text-gray uppercase tracking-wider px-1">Pilotes</p>
          {roster.drivers.map((d) => (
            <DriverCard key={d.uuid} d={d} />
          ))}
        </div>

        <div className="space-y-2 pb-4">
          <p className="text-[10px] text-gray uppercase tracking-wider px-1">Écuries</p>
          {roster.teams.map((t) => (
            <TeamCard key={t.uuid} t={t} />
          ))}
        </div>

      </div>
    </SeasonLayout>
  );
}
