import { getSession } from "@/lib/security";
import { Header } from "../_components/Header";
import { Menu } from "../_components/Menu";
import { BackButton } from "../_components/BackButton";

export default async function Layout({
    children,
}: Readonly<{
    children: React.ReactNode;
}>) {
    const session = await getSession();
    return (
        <>
            {session ?
                <Header isHub={false} />
            :
                <BackButton className="ml-2 my-2" />
            }
            {children}
            {session &&
                <Menu />
            }
        </>
    );
}
