# Roadmap

Ce document tient lieu d'issues tant que le dépôt n'est pas encore poussé sur
GitHub. Chaque ticket ci-dessous deviendra une issue GitHub telle quelle
(titre + description + critères d'acceptation) le jour venu.

## Objectif du projet

Un runner de tests compatible avec le **format d'écriture des tests
PHPUnit** (classes qui étendent `TestCase`, méthodes `testXxx()`,
`assertEquals()`, `setUp()`/`tearDown()`, `@dataProvider`...), mais qui
n'a **plus de dépendance sur le paquet composer `phpunit/phpunit`** :
on exécute nos tests avec notre propre "harness" PHP minimal, toujours
lancé par le vrai binaire `php` (pas de VM/interpréteur maison — voir la
section "Ambition long terme" en bas).

L'orchestrateur Rust existant (découverte des fichiers `*Test.php`,
répartition en lots équilibrés par durée, parallélisation, agrégation des
résultats) est conservé : il n'a pas besoin de connaître PHPUnit, il lui
suffit qu'un process PHP produise un rapport exploitable par fichier.

## Décision de scope (2026-09-03)

Deux niveaux étaient envisagés :
1. **Retenu** : remplacer uniquement le paquet `phpunit/phpunit` par un
   harness PHP maison, en gardant `php` comme moteur d'exécution.
2. Écarté pour l'instant : remplacer aussi `php` lui-même par un
   interpréteur écrit en Rust. Aucun projet Rust n'est jamais allé
   jusqu'à l'exécution réelle de PHP (Tagua VM archivé en 2019, resté au
   stade parser ; PXP abandonné en 2024 par épuisement de son mainteneur
   solo). Cette option reste un objectif long terme possible si le
   projet attire des contributeurs, mais ne doit pas bloquer un premier
   livrable utile.

## Jalons

### v0.1.0 — Harness minimal viable
Premier test PHPUnit-like qui tourne de bout en bout sans
`phpunit/phpunit` installé.

- [x] **#1 Choisir le nom définitif du projet.** 

- [ ] **#2 Classe `TestCase` maison** (PHP). Propriétés/méthodes minimales :
  constructeur, `setUp()`/`tearDown()` appelés automatiquement autour de
  chaque test, une méthode `run()` qui exécute une méthode `testXxx`
  donnée et capture succès/échec/exception.
- [ ] **#3 Jeu d'assertions de base** (PHP) : `assertEquals`, `assertSame`,
  `assertTrue`, `assertFalse`, `assertNull`, `assertNotNull`, `assertCount`,
  `assertInstanceOf`. Chaque échec d'assertion lève une exception dédiée
  (ex. `AssertionFailedException`) portant message + fichier + ligne.
- [ ] **#4 Autoloader/bootstrap du harness.** Script PHP (invoqué par le
  Rust à la place de `vendor/bin/phpunit`) qui : charge l'autoload
  composer du projet cible (PSR-4, pour que les classes de test et le code
  applicatif se chargent), inclut le fichier de test reçu en argument,
  découvre par réflexion les méthodes `public function testXxx()`, les
  exécute une à une via `TestCase::run()`.
- [ ] **#5 Format de rapport machine-lisible.** Le harness doit produire un
  rapport que le binaire Rust peut parser. Réutiliser le format JUnit XML
  déjà consommé par `parse_junit()` dans `main.rs` pour ne pas toucher à
  l'orchestrateur — ou définir un format plus simple (JSON ligne par
  ligne) si plus simple à générer côté PHP ; à trancher au moment de
  coder #4/#5 ensemble.
- [ ] **#6 Adapter `main.rs`** pour invoquer le nouveau harness (chemin du
  script bootstrap) au lieu de chercher `vendor/bin/phpunit`. Supprimer
  `find_phpunit_bin()` une fois le harness posé.
- [ ] **#7 Test de bout en bout.** Un fichier `ExampleTest.php` minimal (une
  classe, une méthode `testXxx`, une assertion) qui passe et un qui échoue
  intentionnellement, vérifiés manuellement puis via un test d'intégration
  Rust (`tests/tests.rs`, cf. #12).

### v0.2.0 — Couverture fonctionnelle courante
- [ ] **#8 `@dataProvider` / `#[DataProvider]`.** Support des deux syntaxes
  (annotation docblock historique et attribut PHP 8). Un test avec
  provider doit apparaître comme N cas distincts dans le rapport.
- [ ] **#9 `expectException()` / `expectExceptionMessage()`.**
- [ ] **#10 Tests marqués skip/incomplete** (`markTestSkipped`,
  `markTestIncomplete`).
- [ ] **#11 Assertions étendues** : `assertStringContainsString`,
  `assertArrayHasKey`, `assertGreaterThan`/`assertLessThan`,
  `assertMatchesRegularExpression`, `assertJsonStringEqualsJsonString`.
  Prioriser celles effectivement utilisées dans mdf-api-full (`grep -r
  "->assert" tests/` sur ce projet pour objectiver la liste plutôt que
  deviner).

### v0.3.0 — Fiabilité et ergonomie
- [ ] **#12 Tests d'intégration Rust** (`tests/tests.rs` + fixtures PHP
  factices dans `tests/testenv/`), suivant la convention fd relevée en
  recherche — pas de vrai projet Symfony nécessaire pour tester le
  runner lui-même.
- [ ] **#13 Messages d'erreur avec contexte** : diff lisible sur
  `assertEquals` (attendu/obtenu), pas juste "assertion failed".
- [ ] **#14 Documentation utilisateur** : README avec exemple minimal,
  liste des assertions supportées vs non supportées (comparé à PHPUnit),
  pour que des contributeurs sachent où contribuer.

### v1.0.0 — Prêt pour contribution externe
- [ ] **#15 CI GitHub Actions** (fmt + clippy + tests, cf. section
  recherche : modèle fd `CICD.yml`).
- [ ] **#16 CONTRIBUTING.md finalisé**, CHANGELOG.md démarré (conventional
  commits + `release-plz` recommandé par la recherche pour automatiser
  versioning/CHANGELOG en CI).
- [ ] **#17 Dogfooding sur mdf-api-full** : remplacer l'usage actuel de
  `vendor/bin/phpunit` par ce harness sur un sous-ensemble réel de tests
  fonctionnels, mesurer l'écart de couverture d'assertions.

## Ambition long terme (hors scope actuel, non planifiée)

Un interpréteur PHP écrit en Rust, pour ne plus dépendre du binaire `php`
du tout. Volontairement non planifié en jalons tant que v1.0.0 n'est pas
atteint : c'est un projet d'une tout autre ampleur (comparable à
`php-src`/HHVM d'après la recherche menée), et le risque documenté sur les
tentatives précédentes (PXP, Tagua VM) est l'épuisement d'un mainteneur
solo — d'où l'intérêt de d'abord livrer quelque chose d'utile et
contributif avant d'attaquer ça, si jamais.
