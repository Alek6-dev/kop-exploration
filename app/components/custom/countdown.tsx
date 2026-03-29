"use client"

import { usePathname, useRouter } from "next/navigation";
import { useEffect, useRef, useState } from "react";

export interface RoundCountdownProps {
    roundEndDate: Date,
    timeRemaining: number,
    isStrategyCountdown?: boolean
}

const Countdown = ({ roundEndDate, timeRemaining, isStrategyCountdown }: RoundCountdownProps) => {
    const Ref = useRef<ReturnType<typeof setInterval> | null>(null);
    const router = useRouter();
    const pathname = usePathname();

    // The state for our timer
    const [timer, setTimer] = useState("");

    let timeRemainingTotal = timeRemaining;

    const getTimeRemaining = () => {
        const total = timeRemainingTotal - 1000;
        const seconds = Math.floor((total / 1000) % 60);
        const minutes = Math.floor(
            (total / 1000 / 60) % 60
        );
        const hours = Math.floor(
            (total / 1000 / 60 / 60) % 24
        );
        const days = Math.floor(
            (total / 1000 / 60 / 60 / 24) % 24
        );
        timeRemainingTotal = total;
        return {
            total,
            days,
            hours,
            minutes,
            seconds,
        };
    };

    const startTimer = () => {
        let { total, days, hours, minutes, seconds } = getTimeRemaining();
        if(total < 0) {
            if(Ref.current) {
                clearInterval(Ref.current);
                setTimeout(() => { router.push(pathname) }, 2000);
                // Disable click on radio buttons and submit button when countdown is over
                if(isStrategyCountdown) {
                    const radioInput = document.querySelectorAll(".custom-radio-item-driver input");
                    const radioButtons = document.querySelectorAll(".custom-radio-item-driver button");
                    const buttonSubmit = document.querySelector("#button-submit-strategy");
                    const buttonsBonus = document.querySelectorAll(".button-bonus");
                    const selectPlayer = document.querySelector(".select-player button");
                    buttonSubmit?.remove();
                    buttonsBonus.forEach((buttonBonus) => {
                        buttonBonus.remove();
                    });
                    radioInput.forEach((radioInput) => {
                        (radioInput as HTMLInputElement).disabled = true;
                    });
                    radioButtons.forEach((radioButton) => {
                        (radioButton as HTMLButtonElement).disabled = true;
                    });
                    if(selectPlayer) {
                        (selectPlayer as HTMLButtonElement).disabled = true;
                        (selectPlayer as HTMLButtonElement).classList.add("pointer-events-none");
                    }
                }
            }
            return;
        }
        setTimer(
            (days > 0 ? days+"j " : "") +
            (hours > 9 ? hours : "0" + hours) +
            "h " +
            (minutes > 9
                ? minutes
                : "0" + minutes) +
            "m " +
            (seconds > 9 ? seconds : "0" + seconds) + "s"
        );
    };

    const clearTimer = (e: any) => {
        // If you adjust it you should also need to
        // adjust the Endtime formula we are about
        // to code next
        //setTimer("00:00:10");

        // If you try to remove this line the
        // updating of timer Variable will be
        // after 1000ms or 1sec
        if (Ref.current) clearInterval(Ref.current);
        const id = setInterval(() => {
            startTimer();
        }, 1000);
        Ref.current = id;
    };

    const getDeadTime = () => {
        let deadline = roundEndDate;

        // This is where you need to adjust if
        // you entend to add more time
        //deadline.setSeconds(deadline.getSeconds() + 10);
        return deadline;
    };

    // We can use useEffect so that when the component
    // mount the timer will start as soon as possible

    // We put empty array to act as componentDid
    // mount only
    useEffect(() => {
        clearTimer(getDeadTime());
    }, []);


    return (
        <b>{timer}</b>
    );
}

export { Countdown };
