import { Header } from "../_components/Header";
import { Menu } from "../_components/Menu";

export default async function Layout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {

  return (
    <>
      <Header isHub={false} />
      {children}
      <Menu />
    </>
  );
}
