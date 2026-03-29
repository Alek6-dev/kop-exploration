import { Block } from "./block";
import Image from "next/image";

interface LinkBlockProps {
    title: string,
    url: string,
    description?: string,
}

const LinkBlock = ({title, url, description}: LinkBlockProps) => {
    return(

        <Block containerClassName="block-animation mb-4" childClassName="px-4 py-3 min-h-12 justify-center">
            <a href={url} className="flex flex-row justify-between align-middle items-center">
                <div>
                    <h3 className="text-medium font-bold">{title}</h3>
                    <p className="text-gray">{description}</p>
                </div>
                <div className="relative rounded-full h-[24px] w-[24px] flex-centering bg-white">
                    <Image
                        src="/assets/icons/arrow-in-circle.svg"
                        alt=""
                        quality={100}
                        width={7}
                        height={10}
                    />
                </div>
            </a>
        </Block>
    )
}

export { LinkBlock };
