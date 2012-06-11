Montserrat
==========

What is it?
-----------

Montserrat is to [Behat](http://behat.org) what [Aruba](https://github.com/cucumber/aruba) is to [Cucumber](http://cukes.info/). It's an extension for Command
line applications written in any programming language. I'll be trying to add as
much of aruba's functionality as I can on an ongoing basis.

Installation
------------

The only documented way to install montserrat is with
[composer](http://getcomposer.org)

``` bash
$ composer.phar require --dev davedevelopment/montserrat:* 
```

Usage
-----

Add the extension to your `behat.yml` file:

``` yaml
default:
    extensions:
        Behat\Montserrat\Extension:

```

In your `FeatureContext` constructor, add montserrat as a context (traits coming soon):

``` php
<?php
    public function __construct(array $parameters)
    {
        $this->useContext('montserrat', new Behat\Montserrat\Context\MontserratContext());
    }
```

Write your features:

``` gherkin
Feature: ls
    In order to examine files in directory
    As a terminal user
    I need to list the files

    Scenario: ls shows files
        Given an empty file named "foo/bar"
        When I run `ls foo`
        Then the output should contain "bar"
```

To see a full list of available steps, see the `features`, or use behat's `-dl` switch:

``` bash
$ vendor/bin/behat -dl

```

Copyright
---------

Copyright (c) 2012 Dave Marshall. See LICENCE for further details
