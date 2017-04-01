# FEATURES

## DOMAIN

Our domain regards a collection of `things`, where a `thing` could be one of the following thigs:

- conferences/events
- middleware
- chocolate wrappers

## FEATURES

### LIST OF `THINGS`

Without authentication a user will be able to see the list of all the `things`.

### `THING` DETAIL

Without authentication a user will be able to see the detail of a `thing`.

### SUBMIT A NEW `THING`

A user needs to be authenticated to propose a new `thing`.

The data submitted by the user needs to be filtered and validated.

### ACCEPT A NEW `THING`

A user needs to be authorized to accept a new `thing`.

Only after approval the `thing` will be added to the public list

### MULTILANGUAGE

Results will need to be served in several languages

### ERROR HANDLING/LOGGING

In development we will use `Whoops` as error handler.

In production we will need to do otherwise. Moreover, we will need to log accesses and errors.

### CACHE

Add a cache layer around the `list` and the `detail` requests.

### VERSIONING

Produce a new version of the same API without touching the old one

### CONTENT-NEGOTIATION

`Content-type` header and `Accept` header