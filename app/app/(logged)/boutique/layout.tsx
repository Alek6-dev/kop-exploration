"use server"

import language from "@/messages/fr";
import { ShopTitle } from "./components/ShopTitle";

interface  LayoutProps {
  children: React.ReactNode,
  params: { uuid: string }
}

export default async function Layout({ children, params : { uuid }} : LayoutProps) {
  return (
    <main>
      <ShopTitle title={language.shop.title} />
      {children}
    </main>
  );
}
