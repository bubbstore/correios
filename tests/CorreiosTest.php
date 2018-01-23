<?php

namespace bubbstore\Correios;

use PHPUnit_Framework_TestCase;
use bubbstore\Correios\CorreiosTracking;
use bubbstore\Correios\Exceptions\CorreiosTrackingException;

class CorreiosTest extends PHPUnit_Framework_TestCase
{

	public function test_correios_valid_tracking()
	{

		$tracking = new CorreiosTracking('PP303300515BR');
		$result = $tracking->find();
	
		$this->assertArrayHasKey('code', $result);
		$this->assertArrayHasKey('last_timestamp', $result);
		$this->assertArrayHasKey('last_status', $result);
		$this->assertArrayHasKey('last_date', $result);
		$this->assertArrayHasKey('last_locale', $result);
		$this->assertArrayHasKey('delivered', $result);
		$this->assertArrayHasKey('delivered_at', $result);
		$this->assertArrayHasKey('tracking', $result);

	}

	public function test_correios_invalid_tracking()
	{

		$this->expectException(CorreiosTrackingException::class);

		$tracking = new CorreiosTracking('JJJJJJJJ');
		$result = $tracking->find();
	}

}