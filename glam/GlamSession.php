<?php

namespace Glam;

use Dot\Http\SessionInterface;

class GlamSession implements SessionInterface {
	function set(string $name, $value){
		\set_session($name, $value);
		return $this;
	}

	function get(string $name){
		return \get_session($name);
	}
}