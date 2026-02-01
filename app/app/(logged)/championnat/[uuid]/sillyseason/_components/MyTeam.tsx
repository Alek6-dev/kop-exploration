"use client"

import React from 'react';
import language from '@/messages/fr';
import { Separator } from '@/components/custom/separator';
import { PopinOpener } from '@/components/custom/popinOpener';
import { MyTeamItem } from './MyTeamItem';

export interface SelectedItem {
    name: string,
    image: string,
    color: string,
}

export interface MyTeamProps {
    bettingRound: number,
    selectedDriver1: SelectedItem|null,
    selectedDriver2: SelectedItem|null,
    selectedTeam: SelectedItem|null,
}

const MyTeam = ({ bettingRound, selectedDriver1=null, selectedDriver2=null, selectedTeam=null }: MyTeamProps ) => {
    return (
        <>
            {bettingRound < 2 &&
                <>
                    <Separator />
                    <div className="p-4">
                    <p className="mb-1 text-gray">{language.championship.sillyseason.myteam.empty}</p>
                    </div>
                </>
            }
            {bettingRound > 1 &&
                <div className="grid grid-cols-3 gap-4 px-4 pb-3">
                    <MyTeamItem name={selectedDriver1?.name} image={selectedDriver1?.image} color={selectedDriver1?.color} type="driver" />
                    <MyTeamItem name={selectedDriver2?.name} image={selectedDriver2?.image} color={selectedDriver2?.color} type="driver" />
                    <MyTeamItem name={selectedTeam?.name} image={selectedTeam?.image} color={selectedTeam?.color} type="team" />
                </div>
            }
        </>
    )
}

export { MyTeam };
