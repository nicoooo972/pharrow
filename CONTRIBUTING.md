# Contribuer

Merci de vouloir contribuer. Le projet en est aux tout premiers pas — le
[ROADMAP.md](ROADMAP.md) liste les tickets ouverts par jalon, chacun avec
des critères d'acceptation. C'est le meilleur point de départ.

## Process

1. Ouvre une issue (ou commente un ticket existant du ROADMAP une fois migré
   en issues GitHub) avant de commencer du travail non trivial, pour éviter
   le travail en double ou une direction qui ne conviendrait pas.
2. Une PR doit :
   - compiler sans warning (`cargo clippy --all-features -- -Dwarnings`)
   - être formatée (`cargo fmt`)
   - passer les tests existants (`cargo test`)
   - mettre à jour `CHANGELOG.md` si le changement est visible pour les
     utilisateurs (nouvelle assertion supportée, changement de format de
     rapport, etc.)
3. Décris le *pourquoi* dans la description de la PR, pas seulement le quoi.

## Utilisation d'IA générative

Si une PR a été rédigée avec l'aide d'un outil d'IA générative (Claude,
Copilot, ChatGPT...), dis-le simplement dans la description. Ce n'est pas
disqualifiant, mais la transparence est requise — et le contributeur reste
responsable de comprendre et pouvoir expliquer le code proposé.

## Portée actuelle du projet

Le scope court terme (voir ROADMAP.md) est volontairement limité : un
harness PHP qui remplace le paquet `phpunit/phpunit`, tout en gardant `php`
comme moteur d'exécution. Les PR qui élargissent vers un interpréteur PHP
autonome (sans `php` du tout) seront orientées vers une discussion d'issue
séparée avant tout code — c'est un changement d'ampleur, pas une
fonctionnalité incrémentale.
