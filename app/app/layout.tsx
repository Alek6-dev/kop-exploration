import type { Metadata, Viewport } from "next";
import "@/styles/globals.css";
import { Toaster } from "@/components/ui/toaster";
import { getSession } from "@/lib/security";
import { useEffect } from 'react';
import { ServiceWorker } from "./_components/ServiceWorker";
import { Button } from "@/components/ui/button";
import { SubscriptionNotif } from "./_components/SubscriptionNotif";

export const metadata: Metadata = {
  title: "King of Paddock",
  description: "F1 Fantasy League",
};

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  maximumScale: 1,
  userScalable: false,
}

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const session = await getSession();

  return (
    <html lang="fr">
      <head>
        <link rel="icon" href="/assets/favicon/favicon.ico" sizes="any" />
        <link rel="apple-touch-icon" href="/assets/favicon/apple-touch-icon.png?<generated>" type="image/<generated>" sizes="<generated>" />
        <link rel="manifest" href="assets/favicon/site.webmanifest" />
        <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#f1c445" />
        <meta name="msapplication-TileColor" content="#F1C445" />
        <meta name="theme-color" content="#ffffff" />
      </head>
      <body className={session ? "user-logged" : ""}>
        <ServiceWorker />
        <SubscriptionNotif />
        {children}
        <Toaster />
      </body>
    </html>
  );
}
