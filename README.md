# Middleware architectures in PHP with Zend Expressive

[![Build Status](https://travis-ci.org/mvlabs/ze-workshop.svg?branch=master)](https://travis-ci.org/mvlabs/ze-workshop)

## Installation

Clone this repository with

```bash
git clone git@github.com:mvlabs/ze-workshop.git
```

and then

```bash
cd ze-workshop
```

## Getting started

Start our new Expressive project with composer:

```bash
bin/composer create-project zendframework/zend-expressive-skeleton app/
```

We go through the installation process and we select the following starred options:

- installation type
    - minimal
    - flat *
        - adds `ping` and `home` actions
        - adds templates
    - modular
        - adds `zendframework/zend-expressive-tooling`
            - manage modules
        - slightly different directory structure to emprove modularity

- container
    - Aura.Di
    - Pimple
    - Zend ServiceManager *

- router
    - Aura.Router
    - FastRoute *
    - Zend Router

- template engine
    - Plates *
    - Twig
    - Zend View
    - None

- error handler
    - Whoops *
    - None

Now we need to add our application to the local hosts.
In Linux environment add the following to your `/etc/hosts` file:

```bash
#ze-workshop
127.0.0.1 ze-workshop.local
```

At this point we can start our Docker environment with

```bash
docker-compose up
```

and see the result navigating to [ze-workshop.local](http://ze-workshop.local).

## pgAdmin

Connect to `localhost:5050`

Select `Add new server` and use the following parameters:
    - `Name`: `ze-workshop`
    - `Host name/address`: `postgres`
    - `User name`: `mvlabs`
    - `Password`: `mvlabs`

## Migrations

Run the migrations using

```bash
bin/migrations migrations:migrate
```

## Test

Run the tests using

```bash
bin/phpunit
```