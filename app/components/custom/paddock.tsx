import Image from "next/image";

export interface PaddockProps {
    car: string,
    carColor: string,
    helmet: string
}

const Paddock = ({ car, carColor, helmet }: PaddockProps) => {

    return (
        <div className="w-full flex-centering mb-6 paddock-render-height relative overflow-hidden">
            <div className="relative paddock-render">
                <svg xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="451" height="500" viewBox="0 0 451 500">
                    <defs><linearGradient id="d" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stopOpacity=".5"/><stop offset="100%" stopOpacity=".5"/></linearGradient><linearGradient id="f" x1="50%" x2="50%" y1="0%" y2="65.785%"><stop offset="0%" stopColor="#131313" stopOpacity="0"/><stop offset="100%" stopColor="#080808"/></linearGradient><linearGradient id="i" x1="50%" x2="50%" y1="100%" y2="0%"><stop offset="0%" stopColor="#131313" stopOpacity="0"/><stop offset="100%" stopColor="#080808"/></linearGradient><polygon id="a" points="0 0 450 0 450 500 0 500"/><polygon id="c" points="41 0 365 0 365 143.471 41 184"/><radialGradient id="e" cx="50%" cy="50.108%" r="49.892%" fx="50%" fy="50.108%" gradientTransform="matrix(0 1 -.90041 0 .951 .001)"><stop offset="0%" stopColor="#FFF" stopOpacity=".337"/><stop offset="100%" stopColor="#FFF" stopOpacity="0"/></radialGradient><filter id="h" width="143.4%" height="326%" x="-21.7%" y="-94.3%" filterUnits="objectBoundingBox"><feMorphology in="SourceAlpha" operator="dilate" radius="9" result="shadowSpreadOuter1"/><feOffset in="shadowSpreadOuter1" result="shadowOffsetOuter1"/><feMorphology in="SourceAlpha" radius="9" result="shadowInner"/><feOffset in="shadowInner" result="shadowInner"/><feComposite in="shadowOffsetOuter1" in2="shadowInner" operator="out" result="shadowOffsetOuter1"/><feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="2"/><feColorMatrix in="shadowBlurOuter1" result="shadowMatrixOuter1" values="0 0 0 0 1 0 0 0 0 0.22745098 0 0 0 0 0.22745098 0 0 0 0.5 0"/><feMorphology in="SourceAlpha" operator="dilate" radius="7.5" result="shadowSpreadOuter2"/><feOffset dy="1" in="shadowSpreadOuter2" result="shadowOffsetOuter2"/><feMorphology in="SourceAlpha" radius="7.5" result="shadowInner"/><feOffset dy="1" in="shadowInner" result="shadowInner"/><feComposite in="shadowOffsetOuter2" in2="shadowInner" operator="out" result="shadowOffsetOuter2"/><feGaussianBlur in="shadowOffsetOuter2" result="shadowBlurOuter2" stdDeviation="1.5"/><feColorMatrix in="shadowBlurOuter2" result="shadowMatrixOuter2" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.631965691 0"/><feMorphology in="SourceAlpha" operator="dilate" radius="27.5" result="shadowSpreadOuter3"/><feOffset dy="20" in="shadowSpreadOuter3" result="shadowOffsetOuter3"/><feMorphology in="SourceAlpha" radius="27.5" result="shadowInner"/><feOffset dy="20" in="shadowInner" result="shadowInner"/><feComposite in="shadowOffsetOuter3" in2="shadowInner" operator="out" result="shadowOffsetOuter3"/><feGaussianBlur in="shadowOffsetOuter3" result="shadowBlurOuter3" stdDeviation="20"/><feColorMatrix in="shadowBlurOuter3" result="shadowMatrixOuter3" values="0 0 0 0 1 0 0 0 0 0.22745098 0 0 0 0 0.22745098 0 0 0 0.26447771 0"/><feMerge><feMergeNode in="shadowMatrixOuter1"/><feMergeNode in="shadowMatrixOuter2"/><feMergeNode in="shadowMatrixOuter3"/></feMerge></filter></defs>
                    <g fill="none" fillRule="evenodd" transform="translate(.2)">
                        <mask id="b" fill="#fff"><use xlinkHref="#a"/></mask>
                        <use xlinkHref="#a" fill="#28292B"/>
                        <g mask="url(#b)">
                            <g transform="translate(-41.2)">
                                <polygon fill="#272727" points="41 126 352.713 109 525 161.33 525 275.294 41 375"/>
                                <use xlinkHref="#c" fill="url(#d)"/>
                                <use xlinkHref="#c" fill={carColor} fillOpacity=".52"/>
                                <polygon fill="#1C1B1B" points="365 0 526 0 526 143.647 365 196" transform="matrix(-1 0 0 1 891 0)"/>
                                <rect width="606" height="339" y="48" fill="url(#e)" opacity=".742"/>
                                <rect width="450" height="130" x="41" y="369" fill="url(#f)"/>
                            </g>
                        </g>
                        <rect width="450" height="126" x="-.5" y="50" fill="url(#i)" mask="url(#b)"/><rect width="451" height="50" x="-1" fill="#080808" mask="url(#b)"/>
                    </g>
                </svg>
                <div
                    className="absolute bottom-30 left-[-5%] block w-[120%] -rotate-[11.5deg] h-3 paddock-render-light"
                    style={{
                        backgroundColor: carColor,
                        boxShadow: "0 20px 40px 40px "+carColor+"40, 0 1px 3px 0 rgba(0,0,0,0.63), 0 0 4px 3px "+carColor+"80"
                    }}
                >
                </div>
                <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + car}`} alt="Car" width={340} height={180} className="absolute top-[26.5%] left-[11%] block paddock-render-f1" />
                <div className="absolute bottom-0 left-1/2 block">
                    <Image src={`${process.env.NEXT_PUBLIC_API_URL + '/' + helmet}`} alt="Helmet" width={82} height={100} className="absolute -top-[58px] left-[29%] block paddock-render-helmet" />
                    <svg xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="160" height="208" viewBox="0 0 160 208">
                        <defs>
                            <radialGradient id="podiumtop" cx="50.046%" cy="54.673%" r="194.914%" fx="50.046%" fy="54.673%" gradientTransform="matrix(.00992 .9986 -.27672 .07772 .647 .004)">
                                <stop offset="0%" stopColor="#9F9F9F"/>
                                <stop offset="100%" stopColor="#3E3E3E"/>
                            </radialGradient>
                            <filter id="podiumblur" width="103.6%" height="103.4%" x="-1.8%" y="-1.7%" filterUnits="objectBoundingBox">
                                <feGaussianBlur in="SourceAlpha" result="shadowBlurInner1" stdDeviation=".5"/>
                                <feOffset dy="1" in="shadowBlurInner1" result="shadowOffsetInner1"/>
                                <feComposite in="shadowOffsetInner1" in2="SourceAlpha" k2="-1" k3="1" operator="arithmetic" result="shadowInnerInner1"/>
                                <feColorMatrix in="shadowInnerInner1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.245569616 0"/>
                            </filter>
                            <path id="podiumlogo" d="M62,100.729247 C72.6259726,100.729247 81.2707527,109.374027 81.2707527,120 L90,120 C90,104.560563 77.4394374,92 62,92 C46.5605626,92 34,104.561145 34,120 L42.7292473,120 C42.7292473,109.374027 51.3740274,100.729247 62,100.729247 Z M62.5522465,69 L58.0525147,73.3952324 L54.7402038,76.6306199 L54.6582717,76.5505906 L47,69.0701706 L47,83.5114001 C51.0574176,86.0862558 55.8195695,87.685682 60.9397288,87.9565058 C61.4580381,87.9768031 61.9757536,88 62.5035623,88 C62.5261233,88 62.5474968,87.9982602 62.5700578,87.9982602 C63.0687747,87.9976803 63.5591795,87.9768031 64.0501781,87.9576657 C64.7198836,87.9222904 65.3830582,87.8619785 66.039702,87.7819491 C66.9937948,87.6659647 67.9300762,87.4954674 68.8527023,87.289595 C72.1537327,86.5525135 75.241621,85.262186 78,83.51198 L78,69.0330556 L70.2936377,76.5604493 L62.5522465,69 Z M62.5,65 C63.3284753,65 64,64.3284753 64,63.5 C64,62.6715247 63.3284753,62 62.5,62 C61.6715247,62 61,62.6715247 61,63.5 C61.0005605,64.3290359 61.6715247,65 62.5,65 Z M77.5,67 C78.3284271,67 79,66.3284271 79,65.5 C79,64.6715729 78.3284271,64 77.5,64 C76.6715729,64 76,64.6715729 76,65.5 C76,66.3284271 76.6715729,67 77.5,67 Z M48,65.5 C48,64.6715247 47.3284753,64 46.5,64 C45.6715247,64 45,64.6715247 45,65.5 C45,66.3284753 45.6715247,67 46.5,67 C47.3284753,67 48,66.3284753 48,65.5 Z"/>
                        </defs>
                        <g fill="none" fillRule="evenodd">
                            <polygon fill="#3E3E3E" points="0 30.1 125.053 30.1 125.053 207.017 0 207.017"/>
                            <polygon fill="url(#podiumtop)" points="34.947 0 160 0 125.053 30.1 0 30.1"/>
                            <polygon fill="#313131" points="125.053 30.1 160 0 160 177 125.053 207.014"/>
                            <use xlinkHref="#podiumlogo" fill="#343434"/>
                            <use xlinkHref="#podiumlogo" fill="#000" filter="url(#podiumblur)"/>
                        </g>
                    </svg>
                </div>
            </div>
            <span className="absolute bottom-0 left-0 block gradient-mask-bottom w-full h-20"></span>
        </div>
    )
}

export { Paddock };
