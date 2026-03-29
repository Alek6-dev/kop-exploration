import { cn } from "@/lib/utils";

export interface StatsArray {
    label: string,
    value: number,
    maxValue: number | null,
}

export interface StatsProps {
    label: string,
    value: number,
    maxValue?: number | null,
    className?: String,
}

const Stats = ({ ...props }: StatsProps) => {
    let ratio;
    if(props.maxValue !== null && props.maxValue !== undefined) {
        ratio = props.maxValue !== null ? Math.round((props.value / props.maxValue) * 100) : 0;
    }
    return(
        <div className={cn("p-4 stats-item", props.className)}>
            <p className="h4 text-primary mb-[3px]">{ props.label }</p>
            <h2 className="h3">{ props.value }
                {props.maxValue !== null &&
                    <>
                        <span> / { props.maxValue }</span>
                        {props.maxValue !== undefined && props.maxValue > 0 &&
                            <span className="text-base font-normal text-gray ml-1">({ratio}%)</span>
                        }
                    </>
                }
            </h2>
        </div>
    )
}

export { Stats };
