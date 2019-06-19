<?php

/*Some helper functions for tests*/

function create($class, $attributes = [], $times = null) {
    return factory($class, $times)->create($attributes);
}

/*function make($class, $attributes = [], $times = null) {
	return factory($class, $times)->make($attributes);
}*/

function make($class, $attributes = [], $times = null, $state = null) {
	if ($state) {
		return factory($class, $times)->states($state)->make($attributes);
	}
	return factory($class, $times)->make($attributes);
}