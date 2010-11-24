phing-hipchat
=============

A Phing task for sending messages to a HipChat room

Requirements
------------

* [mandatory] Phing (developed with 2.3dev)
* [mandatory] PHP version 5.x (developed using 5.2.9)
* [mandatory] PHP's cURL module

Example
--------

For a full example see `build.xml`

`<hipchat token="your_token" room="Hippy" from="rcrowe">
    <speak message="Build successful" />
    <speak message="3 warnings" />
</hipchat>`
