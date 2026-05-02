"use client";

import { useState, useEffect, useTransition } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import { SeasonRoster } from "@/actions/season-game/getSeasonParticipation-action";
import { SeasonGPStrategy, saveSeasonGPStrategy } from "@/actions/season-game/seasonStrategy-action";
import { SEASON_GAME_PAGE, SEASON_GAME_COMPOSITION_PAGE } from "@/constants/routing";
import { SeasonDriverCard } from "./SeasonDriverCard";
import { SeasonTeamCard } from "./SeasonTeamCard";
import { HorizontalScroll } from "./HorizontalScroll";

type OpenSlot = "driver1" | "driver2" | "team" | null;

interface SeasonStrategyFormProps {
  raceUuid: string;
  roster: SeasonRoster;
  existingStrategy: SeasonGPStrategy | null;
}

interface SlotCardProps {
  label: string;
  badge?: string;
  imageUrl: string;
  name: string | null;
  color?: string | null;
  isOpen: boolean;
  starred?: boolean;
  onClick: () => void;
  type: "driver" | "team";
}

const SlotCard = ({ label, badge, imageUrl, name, color, isOpen, starred, onClick, type }: SlotCardProps) => {
  const isSelected = name !== null;
  const bgColor = isSelected && color ? color : "rgba(255,255,255,0.06)";
  return (
    <button type="button" onClick={onClick} className="flex flex-col items-center w-full">
      <div className="relative w-full mt-3">
        {badge && (
          <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full gradient-primary-white border border-white/25 shadow-[0_0_10px_0_rgba(241,196,69,0.46)] h-[24px] w-[24px] flex items-center justify-center text-black font-bold text-xs z-10">
            {badge}
          </div>
        )}
        <div
          className={`h-28 w-full rounded-lg overflow-hidden transition-all flex items-center justify-center ${
            type === "driver" ? "items-end pt-4" : "p-2"
          } ${isSelected ? (type === "driver" ? "gradient-avatar-driver" : "gradient-avatar-team") : ""
          } ${!isSelected ? "opacity-50" : ""}`}
          style={{
            backgroundColor: bgColor,
            boxShadow: starred
              ? "0 0 0 2px #F1C445, 0 0 10px rgba(241,196,69,0.35)"
              : isOpen ? "0 0 0 2px rgba(255,255,255,0.25)" : "none",
          }}
        >
          <Image
            src={imageUrl}
            alt=""
            width={100}
            height={100}
            className={type === "driver" ? "block h-full w-full object-contain object-bottom" : "block h-full w-full object-contain"}
          />
        </div>
      </div>
      <div className="text-xs text-primary leading-none mt-3"><b>{label}</b></div>
      <b className="text-center text-sm leading-tight truncate w-full mt-1">{name ?? "—"}</b>
      <span className={`text-xs font-medium px-3 py-1 rounded-full transition-colors mt-2 ${
        isOpen
          ? "bg-primary/20 text-primary"
          : isSelected
          ? "bg-white/15 text-gray"
          : "bg-white/10 text-gray"
      }`}>
        {isOpen ? "Fermer" : isSelected ? "Changer" : "Choisir"}
      </span>
    </button>
  );
};

type SaveStatus = "idle" | "saving" | "saved" | "error";

const SeasonStrategyForm = ({ raceUuid, roster, existingStrategy }: SeasonStrategyFormProps) => {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);
  const [openSlot, setOpenSlot] = useState<OpenSlot>(null);

  const [driver1Uuid, setDriver1Uuid] = useState<string>(
    existingStrategy?.driver1?.driverUuid ?? ""
  );
  const [driver2Uuid, setDriver2Uuid] = useState<string>(
    existingStrategy?.driver2?.driverUuid ?? ""
  );
  const [teamUuid, setTeamUuid] = useState<string>(
    existingStrategy?.team?.teamUuid ?? ""
  );

  // Référence de la dernière stratégie sauvegardée (se met à jour après chaque save)
  const [savedDriver1, setSavedDriver1] = useState(existingStrategy?.driver1?.driverUuid ?? "");
  const [savedDriver2, setSavedDriver2] = useState(existingStrategy?.driver2?.driverUuid ?? "");
  const [savedTeam, setSavedTeam] = useState(existingStrategy?.team?.teamUuid ?? "");

  const hasExistingComplete = !!(existingStrategy?.driver1 && existingStrategy?.driver2 && existingStrategy?.team);
  const [saveStatus, setSaveStatus] = useState<SaveStatus>(hasExistingComplete ? "saved" : "idle");

  const drivers = roster?.drivers ?? [];
  const teams = roster?.teams ?? [];

  const selectedDriver1 = drivers.find((d) => d.driverUuid === driver1Uuid) ?? null;
  const selectedDriver2 = drivers.find((d) => d.driverUuid === driver2Uuid) ?? null;
  const selectedTeam = teams.find((t) => t.teamUuid === teamUuid) ?? null;

  const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? "";

  const driver1ImageUrl = selectedDriver1?.driverImage
    ? `${apiUrl}/${selectedDriver1.driverImage}`
    : "/assets/images/driver/driver-generic.svg";
  const driver2ImageUrl = selectedDriver2?.driverImage
    ? `${apiUrl}/${selectedDriver2.driverImage}`
    : "/assets/images/driver/driver-generic.svg";
  const teamImageUrl = selectedTeam?.teamImage
    ? `${apiUrl}/${selectedTeam.teamImage}`
    : "/assets/images/team/team-generic.svg";

  const toggleSlot = (slot: OpenSlot) => {
    setOpenSlot((prev) => (prev === slot ? null : slot));
    setSaveStatus("idle");
  };

  const selectDriver1 = (uuid: string) => {
    if (uuid === driver1Uuid) {
      setDriver1Uuid("");
      return; // accordéon reste ouvert pour choisir un autre
    }
    setDriver1Uuid(uuid);
    if (uuid === driver2Uuid) setDriver2Uuid("");
    setOpenSlot(null);
  };

  const selectDriver2 = (uuid: string) => {
    if (uuid === driver2Uuid) {
      setDriver2Uuid("");
      return;
    }
    setDriver2Uuid(uuid);
    setOpenSlot(null);
  };

  const selectTeam = (uuid: string) => {
    if (uuid === teamUuid) {
      setTeamUuid("");
      return;
    }
    setTeamUuid(uuid);
    setOpenSlot(null);
  };

  const isComplete =
    driver1Uuid !== "" && driver2Uuid !== "" && teamUuid !== "" && driver1Uuid !== driver2Uuid;

  const hasChanged =
    driver1Uuid !== savedDriver1 ||
    driver2Uuid !== savedDriver2 ||
    teamUuid !== savedTeam;

  useEffect(() => {
    if (!isComplete || !hasChanged) return;

    setSaveStatus("saving");

    const timer = setTimeout(() => {
      startTransition(async () => {
        const result = await saveSeasonGPStrategy(raceUuid, driver1Uuid, driver2Uuid, teamUuid);
        if (!result.ok) {
          setSaveStatus("error");
          setError(result.error ?? "Erreur lors de la sauvegarde.");
          return;
        }
        setSavedDriver1(driver1Uuid);
        setSavedDriver2(driver2Uuid);
        setSavedTeam(teamUuid);
        setSaveStatus("saved");
        setError(null);
        router.refresh();
      });
    }, 800);

    return () => clearTimeout(timer);
  }, [driver1Uuid, driver2Uuid, teamUuid]);

  if (drivers.length === 0 || teams.length === 0) {
    return (
      <div className="zone mt-4">
        <div className="p-6 text-center">
          <p className="font-semibold mb-1">Équipe introuvable</p>
          <p className="text-sm text-gray mb-4">
            Ton roster ne contient pas encore de pilotes ou d'écuries.
          </p>
          <Link
            href={SEASON_GAME_COMPOSITION_PAGE}
            className="inline-block bg-primary text-black font-bold py-2.5 px-6 rounded-xl text-sm"
          >
            Composer mon équipe
          </Link>
        </div>
      </div>
    );
  }

  if (existingStrategy?.locked) {
    return (
      <div className="zone mt-4">
        <div className="p-6 text-center">
          <p className="font-bold">Stratégie verrouillée</p>
          <p className="text-sm text-gray mt-1">Le délai de modification est passé.</p>
          <div className="mt-4 space-y-2 text-left">
            <div className="flex items-center justify-between py-2 border-b border-white/10">
              <span className="text-xs text-gray uppercase tracking-wider">P1 ×2</span>
              <span className="font-medium">{existingStrategy.driver1?.name ?? "—"}</span>
            </div>
            <div className="flex items-center justify-between py-2 border-b border-white/10">
              <span className="text-xs text-gray uppercase tracking-wider">P2 ×1</span>
              <span className="font-medium">{existingStrategy.driver2?.name ?? "—"}</span>
            </div>
            <div className="flex items-center justify-between py-2">
              <span className="text-xs text-gray uppercase tracking-wider">Écurie</span>
              <span className="font-medium">{existingStrategy.team?.name ?? "—"}</span>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-3 pb-32">

      {/* Zone récap — toujours visible */}
      <div className="zone overflow-hidden">
        <div className="px-4 pt-4 pb-3">
          <p className="text-xs text-gray uppercase tracking-wider mb-3">Ma sélection</p>
          <div className="grid grid-cols-3 gap-2">
            <SlotCard
              label="Pilote n°1"
              badge="×2"
              starred
              imageUrl={driver1ImageUrl}
              name={selectedDriver1?.driverName ?? null}
              color={selectedDriver1?.teamColor ?? null}
              isOpen={openSlot === "driver1"}
              onClick={() => toggleSlot("driver1")}
              type="driver"
            />
            <SlotCard
              label="Pilote n°2"
              imageUrl={driver2ImageUrl}
              name={selectedDriver2?.driverName ?? null}
              color={selectedDriver2?.teamColor ?? null}
              isOpen={openSlot === "driver2"}
              onClick={() => toggleSlot("driver2")}
              type="driver"
            />
            <SlotCard
              label="Écurie"
              imageUrl={teamImageUrl}
              name={selectedTeam?.teamName ?? null}
              color={selectedTeam?.teamColor ?? null}
              isOpen={openSlot === "team"}
              onClick={() => toggleSlot("team")}
              type="team"
            />
          </div>
        </div>
      </div>

      {/* Accordéon Pilote n°1 */}
      {openSlot === "driver1" && (
        <div className="zone">
          <div className="p-4">
            <p className="text-xs uppercase tracking-wider font-semibold mb-3 text-primary">
              Pilote n°1{" "}
              <span className="text-gray font-normal normal-case tracking-normal">- Perf. Pilote ×2</span>
            </p>
            <HorizontalScroll className="custom-radio flex gap-3 overflow-x-auto p-[3px]">
              {drivers.map((d) => (
                <SeasonDriverCard
                  key={d.uuid}
                  driverUuid={d.driverUuid}
                  driverName={d.driverName}
                  driverImage={d.driverImage}
                  teamName={d.teamName}
                  maxUsages={d.maxUsages}
                  usagesLeft={d.usagesLeft}
                  isSelected={driver1Uuid === d.driverUuid}
                  isDisabled={d.usagesLeft === 0}
                  badge="1"
                  onSelect={() => selectDriver1(d.driverUuid)}
                />
              ))}
            </HorizontalScroll>
          </div>
        </div>
      )}

      {/* Accordéon Pilote n°2 */}
      {openSlot === "driver2" && (
        <div className="zone">
          <div className="p-4">
            <p className="text-xs uppercase tracking-wider font-semibold mb-3">
              Pilote n°2{" "}
            </p>
            <HorizontalScroll className="custom-radio custom-radio-neutral flex gap-3 overflow-x-auto p-[3px]">
              {drivers.map((d) => (
                <SeasonDriverCard
                  key={d.uuid}
                  driverUuid={d.driverUuid}
                  driverName={d.driverName}
                  driverImage={d.driverImage}
                  teamName={d.teamName}
                  maxUsages={d.maxUsages}
                  usagesLeft={d.usagesLeft}
                  isSelected={driver2Uuid === d.driverUuid}
                  isDisabled={d.usagesLeft === 0 || d.driverUuid === driver1Uuid}
                  badge="2"
                  onSelect={() => selectDriver2(d.driverUuid)}
                />
              ))}
            </HorizontalScroll>
          </div>
        </div>
      )}

      {/* Accordéon Écurie */}
      {openSlot === "team" && (
        <div className="zone">
          <div className="p-4">
            <p className="text-xs uppercase tracking-wider font-semibold mb-3">
              Écurie{" "}
            </p>
            <div className="custom-radio custom-radio-neutral flex gap-3 p-[3px]">
              {teams.map((t) => (
                <SeasonTeamCard
                  key={t.uuid}
                  teamUuid={t.teamUuid}
                  teamName={t.teamName}
                  teamColor={t.teamColor}
                  teamImage={t.teamImage}
                  maxUsages={t.maxUsages}
                  usagesLeft={t.usagesLeft}
                  isSelected={teamUuid === t.teamUuid}
                  isDisabled={t.usagesLeft === 0}
                  onSelect={() => selectTeam(t.teamUuid)}
                />
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Cartes Bonus — placeholder */}
      <div className="zone opacity-50">
        <div className="p-4 flex items-center justify-between">
          <div>
            <p className="font-semibold text-sm">Cartes Bonus</p>
            <p className="text-xs text-gray">Bientôt disponible</p>
          </div>
          <span className="text-xs bg-white/10 text-gray px-2 py-0.5 rounded-full">À venir</span>
        </div>
      </div>

      {/* Indicateur de statut */}
      {(saveStatus !== "idle" || error) && (
        <div className="fixed bottom-14 pb-4 pt-2 px-4 w-full -ml-4 max-w-[740px] flex justify-center pointer-events-none">
          {error ? (
            <span className="text-xs text-red-400 bg-black/80 px-4 py-2 rounded-full">
              {error}
            </span>
          ) : saveStatus === "saving" || isPending ? (
            <span className="text-xs text-gray bg-black/80 px-4 py-2 rounded-full">
              Sauvegarde…
            </span>
          ) : saveStatus === "saved" ? (
            <span className="text-xs text-primary bg-black/80 px-4 py-2 rounded-full">
              ✓ Stratégie enregistrée
            </span>
          ) : null}
        </div>
      )}
    </div>
  );
};

export { SeasonStrategyForm };
