"use server"

interface  LayoutProps {
  children: React.ReactNode,
  params: { uuid: string }
}

export default async function Layout({ children, params : { uuid }} : LayoutProps) {

  return (
    <main>
      {children}
    </main>
  );
}
