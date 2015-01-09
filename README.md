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

## RabbitMQ consumers

Setup RabbitMQ queues

    php app/console rabbitmq:setup-fabric

Launch RabbitMQ consumers

    php app/console rabbitmq:consumer -w deposit
    php app/console rabbitmq:consumer -w deposit_topup


## Crontabs

Watching our addresses over the blockchain, and add new transactions incoming

    php app/console dizda:blockchain:watch -vvv

Create withdraw from outputs requests

    php app/console dizda:app:withdraw -vvv


This repository is under intensive work, do not use yet.

## Tests

Launch tests suite

    make tests

