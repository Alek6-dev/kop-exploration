import { cn } from "@/lib/utils";
import { ReactNode } from "react";

export interface CarInListingProps {
    width: Number,
    car: string,
    carColor?: string,
    className?: string,
    pictureClassName?: string,
}

const CarInListing = ({ ...props }: CarInListingProps) => {
    return(
        <div className={cn("rounded-l-full relative flex-centering car-in-listing", props.className)} style={{backgroundColor: props.carColor}}>
            <picture className={cn("block w-full translate-x-4", props.pictureClassName)}>
                <source srcSet={`${process.env.NEXT_PUBLIC_API_URL + '/' +  props.car} 1x, ${process.env.NEXT_PUBLIC_API_URL + '/' +  props.car} 2x`} />
                <img src={`${process.env.NEXT_PUBLIC_API_URL} + '/' +  props.car}`} className="relative block max-w-none" alt="" width={props.width.toString()} />
            </picture>
        </div>
    )
}

export { CarInListing };
