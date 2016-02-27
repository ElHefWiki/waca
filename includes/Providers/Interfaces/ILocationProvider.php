<?php

namespace Waca\Providers\Interfaces;

/**
 * IP Location provider interface
 */
interface ILocationProvider
{
	/**
	 * @param string $address IP address
	 *
	 * @return array
	 */
	public function getIpLocation($address);
}
