# Middleware architectures in PHP with Zend Expressive

[![Build Status](https://travis-ci.org/mvlabs/ze-workshop.svg?branch=master)](https://travis-ci.org/mvlabs/ze-workshop)
[![Coverage Status](https://coveralls.io/repos/github/mvlabs/ze-workshop/badge.svg?branch=master)](https://coveralls.io/github/mvlabs/ze-workshop?branch=master)

## Installation

Clone this repository with

```bash
git clone git@github.com:mvlabs/ze-workshop.git
```

and then

```bash
cd ze-workshop
```

## Getting started with Expressive

Start our new Expressive project with composer:

```bash
composer create-project zendframework/zend-expressive-skeleton
```

We go through the installation process and we select the following starred options:

- installation type
    - minimal
    - flat *
    - modular

- container
    - Aura.Di
    - Pimple *
    - Zend ServiceManager

- router
    - Aura.Router
    - FastRoute *
    - Zend Router

- template engine
    - Plates
    - Twig
    - Zend View
    - None *

- error handler
    - Whoops *
    - None

## Add seeds to database

Run

```./bin/seed```
