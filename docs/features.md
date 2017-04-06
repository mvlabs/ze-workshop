# FEATURES

## DOMAIN

Our domain regards a shared collection of chocolate wrappers

## FEATURES

### LIST OF `CHOCOLATE WRAPPERS`

Without authentication a user will be able to see the list of all the `chocolate wrappers`.

### `CHOCOLATE WRAPPER` DETAIL

Without authentication a user will be able to see the detail of a `chocolate wrapper`.

### SUBMIT A NEW `CHOCOLATE WRAPPER`

A user needs to be authenticated to propose a new `chocolate wrapper`.

The data submitted by the user needs to be filtered and validated.

### ACCEPT A NEW `CHOCOLATE WRAPPER`

A user needs to be authorized to accept a new `chocolate wrapper`.

Only after approval the `chocolate wrapper` will be added to the public list

### DELETE A `CHOCOLATE WRAPPER`

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