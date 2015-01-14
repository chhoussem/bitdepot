[![Coverage Status](https://img.shields.io/coveralls/dizda/coinegger.svg)](https://coveralls.io/r/dizda/coinegger)
[![Code Climate](https://codeclimate.com/github/dizda/coinegger/badges/gpa.svg)](https://codeclimate.com/github/dizda/coinegger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dizda/coinegger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dizda/coinegger/?branch=master)
[![Build Status](https://travis-ci.org/dizda/coinegger.svg?branch=master)](https://travis-ci.org/dizda/coinegger)

Coinegger - Work In Progress
========================

When bitcoin meet Arnold Schwarzenegger.
Coinegger is an application-oriented wallet designed for those who run bitcoin websites on their servers.

Features :

- Create multisig wallet
- Derivation to have multiple address with the same seed (BIP32 HDWallet)
- Multi-Application HDWallet (BIP44)
- Watch created addresses when their used using Insight (recommended), or Chain.com
- Save every deposits incoming, and dispatch a callback to an url
- Handle withdraw from multisig addresses
- Group withdraws to save fees
- Sign transactions withdraws through the browser (Client-side with JavaScript, the private key & seed will never going through the network)

Main advantage :

    The private key will never be stored on the server, you can sleep tight.

## Setup

Install dependencies

    bower install
    composer install
    cd node/ && npm install

Then setup the MySQL schema

    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force

## Creating your keychain and your first application

The first step is to create your keychain via the node application:

    node ./node/create_wallet.js

Your keychain handle your applications, so you can sign transactions with the same key across all of your applications.
If you want different password for different applications,

## RabbitMQ consumers

Setup RabbitMQ queues

    php app/console rabbitmq:setup-fabric

Launch RabbitMQ consumers

    php app/console rabbitmq:consumer -w deposit
    php app/console rabbitmq:consumer -w deposit_topup


## Crontabs

Watching our addresses over the blockchain, then add new transactions incoming

    php app/console dizda:blockchain:monitor -vv

Create withdraw from outputs requests

    php app/console dizda:app:withdraw -vv


## Tests

Launch tests suite

    make tests

This repository is under intensive work, do not use in production yet.
If you're interested be part of this project, you can contact me on twitter @dizzda.