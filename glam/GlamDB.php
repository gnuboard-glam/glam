<?php

namespace Glam;

use Dot\DB\Mysql\FunctionalMysql;

class GlamDB extends FunctionalMysql
{
	protected function getHandle()
	{
		global $g5;

		if (empty($g5['connect_db'])) {
			die('GnuBoard does not connected database yet');
		}

		return $g5['connect_db'];
	}

	function open(){
		$this->handle = $this->getHandle();

		return $this;
	}
}