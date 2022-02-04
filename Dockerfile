# syntax=docker/dockerfile:1
FROM pmmp/pocketmine-mp AS download
RUN wget "https://github.com/pmmp/DevTools/raw/e884a4c234629126203e769df7c4dbbbc0dc2d49/src/ConsoleScript.php"
RUN wget -O InfoAPI.phar "https://poggit.pmmp.io/get/InfoAPI"

FROM pmmp/pocketmine-mp AS phar
USER root
COPY ./GlassPain ./phar/GlassPain

COPY --from=download /pocketmine/ConsoleScript.php /pocketmine/ConsoleScript.php

RUN php -dphar.readonly=0 ConsoleScript.php --relative phar/GlassPain --make plugin.yml,src,resources

FROM pmmp/pocketmine-mp AS run
COPY --from=phar /pocketmine/output.phar /plugins/output.phar
COPY --from=download /pocketmine/InfoAPI.phar /plugins/InfoAPI.phar