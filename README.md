phing-hipchat
=============

A Phing task for sending messages to a HipChat room.

Installation
------------

The preferred way of installation is through Composer. Add `rcrowe\phing-hipchat` as a requirement to composer.json:

```javascript
{
    "require": {
        "rcrowe/hippy": "0.6.*"
    }
}
```

Example
-------

Let Phing know about the Hipchat task:

	<taskdef name="hipchat" classname="rcrowe\Hippy\PhingTask" />

**Basic example**

	<hipchat
    	token="23k4l4jkl234jl234kl24"
	    room="Hippy"
    	from="Rob Crowe"
	    notify="false"
    	background="green"
	    msg="build passed" />

All attributes apart from `notify` and `background` are required.

**Properties file**

You can also define the attributes as properties (I like to keep my in a properties file).

	<property name="hipchat.token" value="23k4l4jkl234jl234kl24" />
	<property name="hipchat.room" value="Hippy" />
	<property name="hipchat.from" value="Build Bot" />
	<property name="hipchat.notify" value="true" />
	<property name="hipchat.background" value="random" />

	<hipchat html="<a>Build failed</a>" />

	<hipchat>
    	<queue msg="Test 1" />
	    <queue msg="Test 2" />
    	<queue msg="Test 3" />
	</hipchat>

Tests
-----

To run all tests

    $> ./vendor/bin/phpunit tests

License
-------

phing-hippy is released under the MIT public license.
