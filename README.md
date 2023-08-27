
# Scoreboard Library

This is an example implementation of a scoreboard library with included tests.

## Description

This project is written using full interfaces. The main objective of this implementation is to enable the integration of custom implementations for each class. For instance:

```
Kczereczon\Scoreboard\Repository\MatchRepositoryInterface::class
```

As evident, `Kczereczon\Scoreboard\Utils\Scoreboard::class` requires an implementation of `Kczereczon\Scoreboard\Repository\MatchRepositoryInterface::class`. The scoreboard is decoupled from the data source implementation. Furthermore, an implementation named `Kczereczon\Scoreboard\Repository\InMemoryMatchRepository::class` is provided, utilizing collections as a data storage solution for match information.

You are encouraged to craft your own repository, such as `DoctrineMatchRepository`, or establish an adapter to facilitate alternative means of delivering data to the scoreboard, such as microservices or message queues.

## How to use

While there is no example implementation within this code repository, you can utilize this library as follows:

```php
<?php

include('vendor/autoload.php');

$matchRepository = new InMemoryMatchRepository();
$teamRepository = new InMemoryTeamRepository();

// Save FC Bluetooth team to memory
$teamRepository->save(
    new SimpleTeamEntity(
        1,
        'FC Bluetooth'
    ),
);

// Save NFC Rocks team to memory
$teamRepository->save(
    new SimpleTeamEntity(
        2,
        'NFC Rocks'
    ),
);

// The second argument specifies which statuses will appear on the scoreboard
$scoreBoard = new Scoreboard($matchRepository, [MatchStatus::DURING]);

// Add a new match - this could be done within a CRON job parsing data from third-party sources
$match = new SimpleMatchEntity(
    1,
    $teamRepository->getOneById(1), //FC Bluetooth (home team)
    $teamRepository->getOneById(2), //NFC Rocks (away team)
    0, //initial home score
    0, //initial away score
    new DateTimeImmutable('2023-08-27 12:43:00'), //date of match
    MatchStatus::NOT_STARTED //initial status of match
);

$matchRepository->save(
    $match
);

$matches = $scoreboard->getMatches(); //should return any item

// Start the match - this can also be achieved by subscribing to a webhook from the third-party data provider
$matchRepository->startMatch(1);


$matches = $scoreboard->getMatches(); //now it should return one item
```

### How to render the data

While no implementation of the scoreboard renderer is provided, I recommend creating a new interface named `ScoreboardRendererInterface::class`, which mandates `Kczereczon\Scoreboard\Utils\ScoreboardInterface::class` in its constructor. This interface should encompass `ScoreboardRendererInterface::render` and `ScoreboardRendererInterface::renderSummary` methods.

#### Example implementations of such interface
- `HtmlScoreboardRenderer::class` returns html
- `CliScoreboardRenderer::class` returns string
- `JsonScoreboardRenderer::class` returns json

#### Usage of this kind of renderer (with depenency injection)

```php
<?php

$repository = new InMemoryMatchRepository());
$scoreboard = new Scoreboard($repository);
$renderer = new JsonScoreboardRenderer($scoreboard);


return $renderer->render(); // or $renderer->renderSummary();

```

#### Dependency injection should be used for creating all objects.

## Technologies

- PHP8.2
- PHPUnit



