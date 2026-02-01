import { Container } from "@/components/custom/container";
import { LOGIN_PAGE, CHAMPIONSHIP_CREATE, CHAMPIONSHIP_JOIN } from "@/constants/routing";
import { getSession } from "@/lib/security";
import language from "@/messages/fr";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { ChampionshipsContent } from "./content";
import { Button } from "@/components/ui/button";

export default async function championshipList() {
  const session = await getSession();
  const token = cookies().get("session")?.value;
  const headers = {
    Authorization: `${"Bearer " + token}`,
    "Content-Type": "application/json",
  }

  const fetchData = async () => {
    try {
      const responsesJSON = await Promise.all([
          fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships?isActive=1`, { headers }),
          fetch(`${process.env.NEXT_PUBLIC_REST_URL}/championships?isActive=0`, { headers })
      ]);
      const [activeChampionships, finishedChampionships] = await Promise.all(responsesJSON.map(r => r.json()));
      return [activeChampionships, finishedChampionships];
    } catch (err) {
      throw err;
    }
  };

  const data = await fetchData();

  return (
    <main>
      <Container>
          <h1
              className="h1"
              dangerouslySetInnerHTML={{
                  __html: language.championship.list.title,
              }}
          ></h1>
      </Container>
      <ChampionshipsContent data={data} sessionId={session?.id} />

      <div className="fixed left-0 flex items-end w-full h-20 gap-4 p-4 bottom-14 gradient-mask-bottom">
        <Button className="block-animation" asChild>
          <a href={CHAMPIONSHIP_CREATE}>{language.championship.list.button.create.label}</a>
        </Button>
        <Button className="block-animation" variant="secondary" asChild>
          <a href={CHAMPIONSHIP_JOIN}>{language.championship.list.button.join.label}</a>
        </Button>
      </div>
    </main>
  );
}
