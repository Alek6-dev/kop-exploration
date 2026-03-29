FROM node:18

EXPOSE 3000

RUN apt-get install git dpkg dpkg-dev

ENV HOME /home/kop
WORKDIR $HOME
RUN chmod a+rwx $HOME

ENV GOSU_VERSION 1.10
RUN arch="$(dpkg --print-architecture | awk -F- '{ print $NF }')" \
    && echo $arch \
    && wget -O gosu "https://github.com/tianon/gosu/releases/download/1.10/gosu-$arch" \
    && mv ./gosu /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu


COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod a+x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
