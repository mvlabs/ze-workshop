Source repository for our workshop

# Middleware architectures in PHP with Zend Expressive

## Installation
```bash
$ cd /srv/apps
$ git clone https://github.com/mvlabs/ze-workshop.git
$ cd /srv/apps/ze-workshop
```

### Getting Started

Start our new Expressive project with composer:

```bash
$ bin/composer create-project zendframework/zend-expressive-skeleton app/
```

After choosing and installing the packages, go to the file ``/etc/hosts`` and add:

```bash
#ze-workshop
127.0.0.1 ze-workshop.local
```

At this point, we can build up our docker containers:
```bash
$ docker-compose up --build
```

In a browser we can check that everything is ok visiting: [http://ze-workshop.local](http://ze-workshop.local:8000) 
port: 8000