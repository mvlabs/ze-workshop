# EXPRESSIVE

## SCHEDULE

- installation
	- docker (virtual machine/vagrant backup)
	- git
	- composer

- presentation of the domain/use cases

- expressive flow
	- framework "components" (dependencies)
	- middleware (how to add middleware using http method or pipe)
    - the explicit pipeline using routes.php (no more configuration file)
	- psr7, psr15, psr11 (container)

- installation (& skeleton app)	
	- router fastroute
	- di zend-servicemanager
	- plates
	- error handler whoops

	- how it works
	- directories structure

- integrate domain (branch prepared by us)

- implements some api's (hands on)
    - RESTful in HAL-JSON format
	- test

- add middleware for our use cases
	- authentication
		- basic http
		- jwt
        - oauth2 using https://github.com/thephpleague/oauth2-server that is PSR-7 compliant?
	- session
		- php
		- jwt
	- i18n
	- authorization
	- filtering/validation
	- logging
	- error handling
